<?php

namespace Botble\Marketplace\Providers;

use Assets;
use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Marketplace\Enums\RevenueTypeEnum;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Marketplace\Repositories\Interfaces\VendorInfoInterface;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\Slug\Models\Slug;
use Exception;
use Html;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Language;
use MarketplaceHelper;
use Route;
use SlugHelper;
use Throwable;
use Yajra\DataTables\EloquentDataTable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            add_filter(BASE_FILTER_AFTER_FORM_CREATED, [$this, 'registerAdditionalData'], 128, 2);

            add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveAdditionalData'], 128, 3);

            add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveAdditionalData'], 128, 3);

            add_filter(BASE_FILTER_GET_LIST_DATA, [$this, 'addColumnToEcommerceTable'], 153, 2);
            add_filter(BASE_FILTER_TABLE_HEADINGS, [$this, 'addHeadingToEcommerceTable'], 153, 2);
            add_filter(BASE_FILTER_TABLE_QUERY, [$this, 'modifyQueryInCustomerTable'], 153);

            add_filter(BASE_FILTER_REGISTER_CONTENT_TABS, [$this, 'addBankInfoTab'], 55, 3);
            add_filter(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, [$this, 'addBankInfoContent'], 55, 3);

            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnverifiedVendors'], 130, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getMenuItemCount'], 121);

            if (function_exists('theme_option')) {
                add_action(RENDERING_THEME_OPTIONS_PAGE, [$this, 'addThemeOptions'], 55);
            }

            if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
                add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
                    if (is_in_admin() &&
                        request()->segment(1) === 'vendor' &&
                        auth('customer')->check() &&
                        auth('customer')->user()->is_vendor &&
                        Language::getCurrentAdminLocaleCode() != Language::getDefaultLocaleCode() &&
                        $data &&
                        $data->id &&
                        LanguageAdvancedManager::isSupported($data)
                    ) {
                        $refLang = null;

                        if (Language::getCurrentAdminLocaleCode() != Language::getDefaultLocaleCode()) {
                            $refLang = '?ref_lang=' . Language::getCurrentAdminLocaleCode();
                        }

                        $form->setFormOption(
                            'url',
                            route('marketplace.vendor.language-advanced.save', $data->id) . $refLang
                        );
                    }

                    return $form;
                }, 9999, 2);
            }

            add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, [$this, 'createdByVendorNotification'], 45, 2);
            add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, [$this, 'withdrawalVendorNotification'], 47, 2);

            add_filter(ACTION_BEFORE_POST_ORDER_REFUND_ECOMMERCE, [$this, 'beforeOrderRefund'], 120, 3);
            add_filter(ACTION_AFTER_POST_ORDER_REFUNDED_ECOMMERCE, [$this, 'afterOrderRefunded'], 120, 3);

            add_action('customer_register_validation', function ($request) {
                if (is_plugin_active('marketplace') && $request->input('is_vendor') == 1) {
                    Validator::make(
                        $request->input(),
                        [
                            'shop_name' => 'required|min:2',
                            'shop_phone' => 'required|' . BaseHelper::getPhoneValidationRule(),
                            'shop_url' => 'required',
                        ],
                        [],
                        [
                            'shop_name' => __('Shop Name'),
                            'shop_phone' => __('Shop Phone'),
                            'shop_url' => __('Shop URL'),
                        ]
                    )->validate();

                    $existing = SlugHelper::getSlug(
                        $request->input('shop_url'),
                        SlugHelper::getPrefix(Store::class),
                        Store::class
                    );

                    if ($existing) {
                        throw ValidationException::withMessages([
                            'shop_url' => __('Shop URL is existing. Please choose another one!'),
                        ]);
                    }
                }
            }, 45, 2);
        });
    }

    /**
     * @param BaseHttpResponse $response
     * @param Order $order
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function beforeOrderRefund(BaseHttpResponse $response, Order $order, Request $request)
    {
        $refundAmount = $request->input('refund_amount');
        if ($refundAmount) {
            $store = $order->store;
            if ($store && $store->id) {
                $vendor = $store->customer;
                if ($vendor && $vendor->id) {
                    $vendorInfo = $vendor->vendorInfo;
                    if ($vendorInfo->balance < $refundAmount) {
                        $response
                            ->setError()
                            ->setMessage(trans('plugins/marketplace::order.refund.insufficient_balance', [
                                'balance' => format_price($vendorInfo->balance),
                            ]));
                    }
                }
            }
        }

        return $response;
    }

    /**
     * @param BaseHttpResponse $response
     * @param Order $order
     * @param Request $request
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function afterOrderRefunded(BaseHttpResponse $response, Order $order, Request $request)
    {
        $refundAmount = $request->input('refund_amount');
        if ($refundAmount) {
            $store = $order->store;
            if ($store && $store->id) {
                $vendor = $store->customer;
                if ($vendor && $vendor->id) {
                    $vendorInfo = $vendor->vendorInfo;

                    if ($vendor->balance > $refundAmount) {
                        $revenueRepository = app(RevenueInterface::class);
                        $revenue = $revenueRepository->getModel();

                        $vendorInfo->total_revenue -= $refundAmount;
                        $vendorInfo->balance -= $refundAmount;

                        $data = [
                            'fee' => 0,
                            'currency' => get_application_currency()->title,
                            'current_balance' => $vendor->balance,
                            'customer_id' => $vendor->getKey(),
                            'order_id' => $order->id,
                            'user_id' => Auth::id(),
                            'type' => RevenueTypeEnum::SUBTRACT_AMOUNT,
                            'description' => trans('plugins/marketplace::order.refund.description', [
                                'order' => $order->code,
                            ]),
                            'amount' => $refundAmount,
                            'sub_amount' => $refundAmount,
                        ];

                        DB::beginTransaction();

                        try {
                            $revenue->fill($data);
                            $revenue->save();
                            $vendorInfo->save();

                            DB::commit();
                        } catch (Throwable | Exception $th) {
                            DB::rollBack();

                            return $response
                                ->setError()
                                ->setMessage($th->getMessage());
                        }
                    } else {
                        $response
                            ->setError()
                            ->setMessage(trans('plugins/marketplace::order.refund.insufficient_balance', [
                                'balance' => format_price($vendorInfo->balance),
                            ]));
                    }
                }
            }
        }

        return $response;
    }

    public function addThemeOptions()
    {
        theme_option()
            ->setSection([
                'title' => trans('plugins/marketplace::marketplace.theme_options.name'),
                'desc' => trans('plugins/marketplace::marketplace.theme_options.description'),
                'id' => 'opt-text-subsection-marketplace',
                'subsection' => true,
                'icon' => 'fa fa-shopping-cart',
                'fields' => [
                    [
                        'id' => 'logo_vendor_dashboard',
                        'type' => 'mediaImage',
                        'label' => trans('plugins/marketplace::marketplace.theme_options.logo_vendor_dashboard'),
                        'attributes' => [
                            'name' => 'logo_vendor_dashboard',
                            'value' => null,
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @param FormAbstract $form
     * @param BaseModel $data
     * @throws BindingResolutionException
     */
    public function registerAdditionalData($form, $data)
    {
        if (get_class($data) == Product::class && request()->segment(1) === BaseHelper::getAdminPrefix()) {
            $stores = $this->app->make(StoreInterface::class)->pluck('name', 'id');

            $form->addAfter('status', 'store_id', 'customSelect', [
                'label' => trans('plugins/marketplace::store.forms.store'),
                'label_attr' => ['class' => 'control-label'],
                'choices' => [0 => trans('plugins/marketplace::store.forms.select_store')] + $stores,
            ]);
        } elseif (get_class($data) == Customer::class) {
            if ($data && $data->is_vendor && $form->has('status')) {
                $statusOptions = $form->getField('status')->getOptions();
                $statusOptions['help_block'] = [
                    'text' => trans('plugins/marketplace::marketplace.helpers.customer_status', [
                        'status' => CustomerStatusEnum::ACTIVATED()->label(),
                        'store' => BaseStatusEnum::DRAFT()->label(),
                    ]),
                ];

                $form->modify('status', 'customSelect', $statusOptions);
            }

            $form->addAfter('email', 'is_vendor', 'onOff', [
                'label' => trans('plugins/marketplace::store.forms.is_vendor'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ]);
        }

        return $form;
    }

    /**
     * @param string $type
     * @param Request $request
     * @param BaseModel $object
     * @return bool
     * @throws BindingResolutionException
     */
    public function saveAdditionalData($type, $request, $object)
    {
        if (!is_in_admin()) {
            return false;
        }

        if (in_array($type, [STORE_MODULE_SCREEN_NAME, (new Store())->getTable()])) {
            $customer = $object->customer;
            if ($customer && $customer->id) {
                if ($object->status->getValue() == BaseStatusEnum::PUBLISHED) {
                    $customer->status = CustomerStatusEnum::ACTIVATED;
                } else {
                    $customer->status = CustomerStatusEnum::LOCKED;
                }

                $customer->save();
            }
        } elseif ($type == PRODUCT_MODULE_SCREEN_NAME && $request->has('store_id') && request()->segment(1) !== 'vendor') {
            $object->store_id = $request->input('store_id');
            $object->save();
        } elseif (in_array($type, [CUSTOMER_MODULE_SCREEN_NAME, (new Customer())->getTable()])
            && in_array(Route::currentRouteName(), ['customers.create', 'customers.create.store', 'customers.edit', 'customers.edit.update'])
        ) {
            if ($request->has('is_vendor')) {
                $object->is_vendor = $request->input('is_vendor');
            }

            // Create vendor info
            if ($object->is_vendor && !$object->vendorInfo->id) {
                $this->app->make(VendorInfoInterface::class)
                    ->createOrUpdate([
                        'customer_id' => $object->id,
                    ]);
            }

            if ($object->is_vendor) {
                $store = $object->store;

                if (!$store->name) {
                    $store->name = $object->name;
                }

                if (!$store->phone) {
                    $store->phone = $object->phone;
                }

                if (!$store->logo) {
                    $store->logo = $object->avatar;
                }

                if ($object->status->getValue() == CustomerStatusEnum::ACTIVATED) {
                    $store->status = BaseStatusEnum::PUBLISHED;
                } else {
                    $store->status = BaseStatusEnum::DRAFT;
                }

                $store->save();

                if (!$store->slug) {
                    Slug::create([
                        'reference_type' => Store::class,
                        'reference_id' => $store->id,
                        'key' => Str::slug($store->name),
                        'prefix' => SlugHelper::getPrefix(Store::class),
                    ]);
                }
            }

            $object->save();
        }

        return true;
    }

    /**
     * @param EloquentDataTable $data
     * @param string|Model $model
     * @return EloquentDataTable
     */
    public function addColumnToEcommerceTable($data, $model)
    {
        if (!$model || !is_in_admin(true)) {
            return $data;
        }

        switch (get_class($model)) {
            case Customer::class:
                return $data->addColumn('is_vendor', function ($item) {
                    if (!$item->is_vendor) {
                        return trans('core/base::base.no');
                    }

                    return Html::tag('span', trans('core/base::base.yes'), ['class' => 'text-success']);
                });

            case Order::class:
            case Discount::class:
                return $data
                    ->addColumn('store_id', function ($item) {
                        $store = $item->original_product && $item->original_product->store->name ? $item->original_product->store : $item->store;

                        if (!$store->name) {
                            return '&mdash;';
                        }

                        return Html::link($store->url, $store->name, ['target' => '_blank']);
                    })
                    ->filter(function ($query) use ($model) {
                        $keyword = request()->input('search.value');
                        if ($keyword) {
                            $query = $query
                                ->whereHas('store', function ($subQuery) use ($keyword) {
                                    return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                                });

                            if (get_class($model) == Order::class) {
                                $query = $query
                                    ->whereHas('address', function ($subQuery) use ($keyword) {
                                        return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                                    })
                                    ->orWhereHas('user', function ($subQuery) use ($keyword) {
                                        return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                                    });
                            }

                            return $query;
                        }

                        return $query;
                    });

            case Product::class:
                return $data
                    ->addColumn('store_id', function ($item) {
                        $store = $item->original_product && $item->original_product->store->name ? $item->original_product->store : $item->store;

                        if (!$store->name) {
                            return '&mdash;';
                        }

                        return Html::link($store->url, $store->name, ['target' => '_blank']);
                    })
                    ->filter(function ($query) use ($model) {
                        $keyword = request()->input('search.value');
                        if ($keyword) {
                            $query
                                ->where('name', 'LIKE', '%' . $keyword . '%')
                                ->where('is_variation', 0)
                                ->orWhere(function ($query) use ($keyword) {
                                    $query
                                        ->where('is_variation', 0)
                                        ->where(function ($query) use ($keyword) {
                                            $query
                                                ->orWhere('sku', 'LIKE', '%' . $keyword . '%')
                                                ->orWhere('created_at', 'LIKE', '%' . $keyword . '%')
                                                ->orWhereHas('store', function ($subQuery) use ($keyword) {
                                                    return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                                                });
                                        });
                                });

                            return $query;
                        }

                        return $query;
                    });
        }

        return $data;
    }

    /**
     * @param array $headings
     * @param string|Model $model
     * @return array
     */
    public function addHeadingToEcommerceTable(array $headings, $model): array
    {
        if (!$model || !is_in_admin(true) || Route::is('marketplace.vendors.index')) {
            return $headings;
        }

        switch (get_class($model)) {
            case Customer::class:
                return array_merge($headings, [
                    'is_vendor' => [
                        'name' => 'is_vendor',
                        'title' => trans('plugins/marketplace::store.forms.is_vendor'),
                        'class' => 'text-center',
                        'width' => '100px',
                    ],
                ]);

            case Order::class:
            case Product::class:
            case Discount::class:
                return array_merge($headings, [
                    'store_id' => [
                        'name' => 'store_id',
                        'title' => trans('plugins/marketplace::store.forms.store'),
                        'class' => 'text-start no-sort',
                        'orderable' => false,
                    ],
                ]);
        }

        return $headings;
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function modifyQueryInCustomerTable($query)
    {
        $model = null;

        if ($query instanceof Builder || $query instanceof EloquentBuilder) {
            $model = $query->getModel();
        }

        switch (get_class($model)) {
            case Customer::class:
                return $query->addSelect('is_vendor');

            case Order::class:
            case Product::class:
            case Discount::class:
                return $query->addSelect($model->getTable() . '.store_id')->with(['store']);
        }

        return $query;
    }

    /**
     * @param string $tabs
     * @param BaseModel $data
     * @return string
     */
    public function addBankInfoTab($tabs, $data = null)
    {
        if (!empty($data) && get_class($data) == Store::class && $data->customer->is_vendor) {
            return $tabs .
                view('plugins/marketplace::customers.tax-info-tab')->render() .
                view('plugins/marketplace::customers.payout-info-tab')->render();
        }

        return $tabs;
    }

    /**
     * @param string $tabs
     * @param BaseModel $data
     * @return string
     */
    public function addBankInfoContent($tabs, $data = null)
    {
        if (!empty($data) && get_class($data) == Store::class) {
            $customer = $data->customer;
            if ($customer->is_vendor) {
                return $tabs .
                    view('plugins/marketplace::customers.tax-form', ['model' => $customer])->render() .
                    view('plugins/marketplace::customers.payout-form', ['model' => $customer])->render();
            }
        }

        return $tabs;
    }

    /**
     * @param int $number
     * @param string $menuId
     * @return string
     */
    public function getUnverifiedVendors($number, $menuId)
    {
        switch ($menuId) {
            case 'cms-plugins-marketplace-unverified-vendor':
                if (!Auth::user()->hasPermission('marketplace.unverified-vendor.index')) {
                    return $number;
                }

                if (!MarketplaceHelper::getSetting('verify_vendor', 1)) {
                    return $number;
                }

                $attributes = [
                    'class' => 'badge badge-success menu-item-count unverified-vendors',
                    'style' => 'display: none;',
                ];

                return Html::tag('span', '', $attributes)->toHtml();

            case 'cms-plugins-withdrawal':
                if (!Auth::user()->hasPermission('marketplace.withdrawal.index')) {
                    return $number;
                }

                $attributes = [
                    'class' => 'badge badge-success menu-item-count pending-withdrawals',
                    'style' => 'display: none;',
                ];

                return Html::tag('span', '', $attributes)->toHtml();

            case 'cms-plugins-marketplace':
                if (!Auth::user()->hasAnyPermission([
                    'marketplace.withdrawal.index',
                    'marketplace.unverified-vendor.index',
                ])) {
                    return $number;
                }

                $attributes = [
                    'class' => 'badge badge-success menu-item-count marketplace-notifications-count',
                    'style' => 'display: none;',
                ];

                return Html::tag('span', '', $attributes)->toHtml();

            case 'cms-plugins-ecommerce.product':
                if (!Auth::user()->hasPermission('products.index')) {
                    return $number;
                }

                $attributes = [
                    'class' => 'badge badge-success menu-item-count pending-products',
                    'style' => 'display: none;',
                ];

                return Html::tag('span', '', $attributes)->toHtml();
        }

        return $number;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getMenuItemCount(array $data = []): array
    {
        if (!Auth::check()) {
            return $data;
        }

        $countUnverifiedVendors = 0;

        if (Auth::user()->hasPermission('marketplace.unverified-vendor.index') &&
            MarketplaceHelper::getSetting('verify_vendor', 1)
        ) {
            $countUnverifiedVendors = app(CustomerInterface::class)->count([
                'is_vendor' => true,
                'vendor_verified_at' => null,
            ]);

            $data[] = [
                'key' => 'unverified-vendors',
                'value' => $countUnverifiedVendors,
            ];
        }

        $countPendingWithdrawals = 0;

        if (Auth::user()->hasPermission('marketplace.withdrawal.index')) {
            $countPendingWithdrawals = app(WithdrawalInterface::class)->count([
                ['status', 'IN', [WithdrawalStatusEnum::PENDING, WithdrawalStatusEnum::PROCESSING]],
            ]);

            $data[] = [
                'key' => 'pending-withdrawals',
                'value' => $countPendingWithdrawals,
            ];
        }

        if (Auth::user()->hasAnyPermission(['marketplace.withdrawal.index', 'marketplace.unverified-vendor.index'])) {
            $data[] = [
                'key' => 'marketplace-notifications-count',
                'value' => $countUnverifiedVendors + $countPendingWithdrawals,
            ];
        }

        if (Auth::user()->hasPermission('products.index')) {
            $countPendingProducts = app(ProductInterface::class)->count([
                'status' => BaseStatusEnum::PENDING,
                'created_by_type' => Customer::class,
                ['created_by_id', '!=', 0],
                'approved_by' => 0,
            ]);

            $data[] = [
                'key' => 'pending-products',
                'value' => $countPendingProducts,
            ];

            $pendingOrders = app(OrderInterface::class)->count([
                'status' => BaseStatusEnum::PENDING,
                'is_finished' => 1,
            ]);

            $data[] = [
                'key' => 'ecommerce-count',
                'value' => $pendingOrders + $countPendingProducts,
            ];
        }

        return $data;
    }

    /**
     * @param Request $request
     * @param null $data
     * @return bool
     */
    public function createdByVendorNotification($request, $data = null)
    {
        if (!MarketplaceHelper::getSetting('enable_product_approval', 1)) {
            return false;
        }

        if (!$data instanceof Product || !in_array(Route::currentRouteName(), ['products.create', 'products.edit'])) {
            return false;
        }

        if ($data->created_by_id &&
            $data->created_by_type == Customer::class &&
            Auth::user()->hasPermission('products.edit')
        ) {
            $isApproved = $data->status == BaseStatusEnum::PUBLISHED;
            if (!$isApproved) {
                Assets::addScriptsDirectly(['vendor/core/plugins/marketplace/js/marketplace-product.js']);
            }

            echo view('plugins/marketplace::partials.notification', ['product' => $data, 'isApproved' => $isApproved])
                ->render();

            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @param null $data
     * @return bool
     */
    public function withdrawalVendorNotification($request, $data = null)
    {
        if (!$data instanceof Withdrawal || !in_array(Route::currentRouteName(), ['marketplace.withdrawal.edit'])) {
            return false;
        }

        if (!$data->customer->store || !$data->customer->store->id) {
            return false;
        }

        echo view('plugins/marketplace::withdrawals.store-info', ['store' => $data->customer->store])
            ->render();

        return true;
    }
}
