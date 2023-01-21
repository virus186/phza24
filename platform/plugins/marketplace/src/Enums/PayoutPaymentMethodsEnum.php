<?php

namespace Botble\Marketplace\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static PayoutPaymentMethodsEnum BANK_TRANSFER()
 * @method static PayoutPaymentMethodsEnum PAYPAL()
 */
class PayoutPaymentMethodsEnum extends Enum
{
    public const BANK_TRANSFER = 'bank_transfer';
    public const PAYPAL = 'paypal';

    /**
     * @var string
     */
    public static $langPath = 'plugins/marketplace::marketplace.payout_payment_methods';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::BANK_TRANSFER:
                return Html::tag('span', self::BANK_TRANSFER()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::PAYPAL:
                return Html::tag('span', self::PAYPAL()->label(), ['class' => 'label-primary status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
