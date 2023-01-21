<?php

namespace Botble\Marketplace\Supports;

use Botble\Ecommerce\Enums\DiscountTypeOptionEnum;
use Botble\Ecommerce\Models\Order as OrderModel;
use EmailHandler;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Theme;
use Throwable;

class MarketplaceHelper
{
    /**
     * @param string $view
     * @param array $data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view(string $view, array $data = [])
    {
        return view($this->viewPath($view), $data);
    }

    /**
     * @param string $view
     * @return string
     */
    public function viewPath(string $view): string
    {
        $themeView = Theme::getThemeNamespace() . '::views.marketplace.' . $view;

        if (view()->exists($themeView)) {
            return $themeView;
        }

        return 'plugins/marketplace::themes.' . $view;
    }

    /**
     * @param string $key
     * @param null $default
     * @return string
     */
    public function getSetting($key, $default = '')
    {
        return setting($this->getSettingKey($key), $default);
    }

    /**
     * @param string $key
     * @return string
     */
    public function getSettingKey($key = '')
    {
        return config('plugins.marketplace.general.prefix') . $key;
    }

    /**
     * @return array
     */
    public function discountTypes(): array
    {
        return Arr::except(DiscountTypeOptionEnum::labels(), [DiscountTypeOptionEnum::SAME_PRICE]);
    }

    /**
     * @return string
     */
    public function getAssetVersion(): string
    {
        return '1.0.1';
    }

    /**
     * @return bool
     */
    public function hideStorePhoneNumber(): bool
    {
        return $this->getSetting('hide_store_phone_number', 0) == 1;
    }

    /**
     * @return bool
     */
    public function allowVendorManageShipping(): bool
    {
        return $this->getSetting('allow_vendor_manage_shipping', 0) == 1;
    }

    /**
     * @param Collection $orders
     * @return Collection
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function sendMailToVendorAfterProcessingOrder($orders)
    {
        if ($orders instanceof Collection) {
            $orders->loadMissing(['store']);
        } else {
            $orders = [$orders];
        }

        $mailer = EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME);

        if ($mailer->templateEnabled('store_new_order')) {
            foreach ($orders as $order) {
                if ($order->store->email) {
                    $this->setEmailVendorVariables($order);
                    $mailer->sendUsingTemplate('store_new_order', $order->store->email);
                }
            }
        }

        return $orders;
    }

    /**
     * @param OrderModel $order
     * @return \Botble\Base\Supports\EmailHandler
     * @throws Throwable
     */
    public function setEmailVendorVariables(OrderModel $order): \Botble\Base\Supports\EmailHandler
    {
        return EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'customer_name' => $order->user->name ?: $order->address->name,
                'customer_email' => $order->user->email ?: $order->address->email,
                'customer_phone' => $order->user->phone ?: $order->address->phone,
                'customer_address' => $order->full_address,
                'product_list' => view('plugins/ecommerce::emails.partials.order-detail', compact('order'))
                    ->render(),
                'shipping_method' => $order->shipping_method_name,
                'payment_method' => $order->payment->payment_channel->label(),
                'store_name' => $order->store->name,
            ]);
    }

    public function isCommissionCategoryFeeBasedEnabled(): bool
    {
        return MarketplaceHelper::getSetting('enable_commission_fee_for_each_category') == 1;
    }
}
