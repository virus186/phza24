<?php

namespace Botble\Ecommerce\Services;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Models\Shipping;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Support\Services\Cache\Cache;
use EcommerceHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class HandleShippingFeeService
{
    /**
     * @var ShippingInterface
     */
    protected $shippingRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @var ShippingRuleInterface
     */
    protected $shippingRuleRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var StoreLocatorInterface
     */
    protected $storeLocatorRepository;

    /**
     * @var array
     */
    protected $shipping;

    /**
     * @var BaseModel
     */
    protected $shippingDefault;

    /**
     * @var array
     */
    protected $shippingRules;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var bool
     */
    protected $useCache;

    /**
     * HandleShippingFeeService constructor.
     * @param ShippingInterface $shippingRepository
     * @param AddressInterface $addressRepository
     * @param ShippingRuleInterface $shippingRuleRepository
     * @param ProductInterface $productRepository
     * @param StoreLocatorInterface $storeLocatorRepository
     */
    public function __construct(
        ShippingInterface $shippingRepository,
        AddressInterface $addressRepository,
        ShippingRuleInterface $shippingRuleRepository,
        ProductInterface $productRepository,
        StoreLocatorInterface $storeLocatorRepository
    ) {
        $this->shippingRepository = $shippingRepository;
        $this->addressRepository = $addressRepository;
        $this->shippingRuleRepository = $shippingRuleRepository;
        $this->productRepository = $productRepository;
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->shipping = [];
        $this->shippingRules = [];

        $this->cache = new Cache(app('cache'), self::class);
        $this->useCache = true;
    }

    /**
     * @param array $data
     * @param null $method
     * @param null $option
     * @return array
     */
    public function execute(array $data, $method = null, $option = null): array
    {
        $result = [];

        $cacheKey = $this->getCacheKey($data);
        $cacheValue = $this->getCacheValue($cacheKey);
        if ($cacheValue) {
            $result = $cacheValue;
        } else {
            $default = $this->getShippingFee($data, ShippingMethodEnum::DEFAULT, $option);

            if ($default) {
                $result[ShippingMethodEnum::DEFAULT] = $default;
            }

            $result = apply_filters('handle_shipping_fee', $result, $data, $option);

            $this->setCacheValue($cacheKey, $result);
        }

        if ($method) {
            if ($response = Arr::get($result, $method . '.' . $option)) {
                return [$response];
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @param string $method
     * @param string|null $option
     * @return array
     */
    protected function getShippingFee(array $data, string $method, ?string $option = null): array
    {
        $weight = EcommerceHelper::validateOrderWeight(Arr::get($data, 'weight'));

        $orderTotal = Arr::get($data, 'order_total', 0);

        if (EcommerceHelper::isUsingInMultipleCountries()) {
            $country = Arr::get($data, 'country');
        } else {
            $country = EcommerceHelper::getFirstCountryId();
        }

        $result = [];
        switch ($method) {
            case 'default':
                $methodKey = $method . '-' . $country;
                if (Arr::has($this->shipping, $methodKey)) {
                    $shipping = Arr::get($this->shipping, $methodKey);
                } else {
                    $shipping = $this->shippingRepository->getFirstBy(['country' => $country]);
                    Arr::set($this->shipping, $methodKey, $shipping);
                }

                if (!empty($shipping)) {
                    $result = $this->calculateDefaultFeeByAddress(
                        $shipping,
                        $weight,
                        $orderTotal,
                        Arr::get($data, 'city'),
                        $option
                    );
                }

                if (empty($result)) {
                    if ($this->shippingDefault) {
                        $default = $this->shippingDefault;
                    } else {
                        $default = $this->shippingRepository
                            ->getModel()
                            ->whereNull('country')
                            ->first();
                        $this->shippingDefault = $default;
                    }

                    $result = $this->calculateDefaultFeeByAddress(
                        $default,
                        $weight,
                        $orderTotal,
                        Arr::get($data, 'city'),
                        $option
                    );
                }

                break;
        }

        if ($result) {
            $result = collect($result)->sortBy('price')->toArray();
        }

        return $result;
    }

    /**
     * @param Shipping $shipping
     * @param int $weight
     * @param int $orderTotal
     * @param string $city
     * @param null $option
     * @return array
     */
    protected function calculateDefaultFeeByAddress($shipping, $weight, $orderTotal, $city, $option = null): array
    {
        $result = [];

        if ($shipping) {
            $ruleKey = 'rule-option-' . $option;
            if (Arr::has($this->shippingRules, $ruleKey)) {
                $rule = Arr::get($this->shippingRules, $ruleKey);
            } else {
                $rule = $this->shippingRuleRepository->findById($option, ['items']);
                Arr::set($this->shippingRules, $ruleKey, $rule);
            }

            if ($rule) {
                $ruleDetail = $rule
                    ->items
                    ->where('city', $city)
                    ->where('is_enabled', 1)
                    ->first();
                if ($ruleDetail) {
                    $result[$rule->id] = [
                        'name' => $rule->name,
                        'price' => $rule->price + $ruleDetail->adjustment_price,
                    ];
                } else {
                    $result[$rule->id] = [
                        'name' => $rule->name,
                        'price' => $rule->price,
                    ];
                }
            } else {
                $rules = $this->shippingRuleRepository
                    ->getModel()
                    ->where(function (Builder $query) use ($orderTotal, $shipping) {
                        $query
                            ->where('shipping_id', $shipping->id)
                            ->where('type', 'base_on_price')
                            ->where('from', '<=', $orderTotal)
                            ->where(function (Builder $sub) use ($orderTotal) {
                                $sub
                                    ->whereNull('to')
                                    // ->orWhere('to', '<=', 0)
                                    ->orWhere('to', '>=', $orderTotal);
                            });
                    })
                    ->orWhere(function (Builder $query) use ($weight, $shipping) {
                        $query
                            ->where('shipping_id', $shipping->id)
                            ->where('type', 'base_on_weight')
                            ->where('from', '<=', $weight)
                            ->where(function (Builder $sub) use ($weight) {
                                $sub
                                    ->whereNull('to')
                                    // ->orWhere('to', '<=', 0)
                                    ->orWhere('to', '>=', $weight);
                            });
                    })
                    ->with(['items'])
                    ->get();

                foreach ($rules as $rule) {
                    /**
                     * @var ShippingRule $rule
                     */
                    $ruleDetail = $rule
                        ->items
                        ->where('city', $city)
                        ->where('is_enabled', 1)
                        ->first();
                    if ($ruleDetail) {
                        $result[$rule->id] = [
                            'name' => $rule->name,
                            'price' => $rule->price + $ruleDetail->adjustment_price,
                        ];
                    } else {
                        $result[$rule->id] = [
                            'name' => $rule->name,
                            'price' => $rule->price,
                        ];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function getCacheKey(array $data): string
    {
        $jsonData = json_encode(Arr::only($data, ['origin', 'address_to', 'parcels', 'extra']));

        return md5($jsonData);
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function getCacheValue(string $key)
    {
        if ($this->useCache) {
            return $this->cache->get($key);
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    protected function setCacheValue(string $key, $value): bool
    {
        if ($key) {
            return $this->cache->put($key, $value);
        }

        return true;
    }
}
