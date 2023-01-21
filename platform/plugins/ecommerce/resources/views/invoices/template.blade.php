<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ trans('plugins/ecommerce::order.invoice_for_order') }} {{ $invoice->code }}</title>

    @if (get_ecommerce_setting('using_custom_font_for_invoice', 0) == 1 && get_ecommerce_setting('invoice_font_family'))
        <link href="https://fonts.googleapis.com/css?family={{ urlencode(get_ecommerce_setting('invoice_font_family')) }}:400,500,600,700,900&display=swap" rel="stylesheet">
    @endif
    <style>
        body {
            font-size: 15px;
            font-family: '{{ get_ecommerce_setting('using_custom_font_for_invoice', 0) == 1 ? get_ecommerce_setting('invoice_font_family', 'DejaVu Sans') : 'DejaVu Sans' }}', Arial, sans-serif !important;
        }

        table {
            border-collapse : collapse;
            width           : 100%
        }

        table tr td {
            padding : 0
        }

        table tr td:last-child {
            text-align : right
        }

        .bold, strong {
            font-weight : 700
        }

        .right {
            text-align : right
        }

        .large {
            font-size : 1.75em
        }

        .total {
            color       : #fb7578;
            font-weight : 700
        }

        .logo-container {
            margin : 20px 0 50px
        }

        .invoice-info-container {
            font-size : .875em
        }

        .invoice-info-container td {
            padding : 4px 0
        }

        .line-items-container {
            font-size : .875em;
            margin    : 70px 0
        }

        .line-items-container th {
            border-bottom  : 2px solid #ddd;
            color          : #999;
            font-size      : .75em;
            padding        : 10px 0 15px;
            text-align     : left;
            text-transform : uppercase
        }

        .line-items-container th:last-child {
            text-align : right
        }

        .line-items-container td {
            padding : 10px 0
        }

        .line-items-container tbody tr:first-child td {
            padding-top : 25px
        }

        .line-items-container.has-bottom-border tbody tr:last-child td {
            border-bottom  : 2px solid #ddd;
            padding-bottom : 25px
        }

        .line-items-container th.heading-quantity {
            width : 50px
        }

        .line-items-container th.heading-price {
            text-align : right;
            width      : 100px
        }

        .line-items-container th.heading-subtotal {
            width : 100px
        }

        .payment-info {
            font-size   : .875em;
            line-height : 1.5;
            width       : 38%
        }

        small {
            font-size : 80%
        }

        .stamp {
            border         : 2px solid #555;
            color          : #555;
            display        : inline-block;
            font-size      : 18px;
            font-weight    : 700;
            left           : 30%;
            line-height    : 1;
            opacity        : .5;
            padding        : .3rem .75rem;
            position       : fixed;
            text-transform : uppercase;
            top            : 40%;
            transform      : rotate(-14deg)
        }

        .is-failed {
            border-color : #d23;
            color        : #d23
        }

        .is-completed {
            border-color : #0a9928;
            color        : #0a9928
        }
    </style>
</head>
<body>

@if (get_ecommerce_setting('enable_invoice_stamp', 1) == 1)
    @if ($invoice->status == \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED && trim($invoice->status->label()))
        <span class="stamp is-failed">
            {{ $invoice->status->label() }}
        </span>

    @elseif (trim($invoice->payment->status->label()))
        <span class="stamp @if ($invoice->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::COMPLETED) is-completed @else is-failed @endif">
            {{ $invoice->payment->status->label() }}
        </span>
    @endif
@endif

@php
    $logo = get_ecommerce_setting('company_logo_for_invoicing') ?: (theme_option('logo_in_invoices') ?: theme_option('logo'));
@endphp

<table class="invoice-info-container">
    <tr>
        <td>
            <div class="logo-container">
                @if ($logo)
                    <img src="{{ RvMedia::getRealPath($logo) }}"
                         style="width:100%; max-width:150px;" alt="{{ get_ecommerce_setting('company_name_for_invoicing') ?: theme_option('site_title') }}">
                @endif
            </div>
        </td>
        <td>
            @if ($invoice->created_at)
                <p><strong>{{ $invoice->created_at->format('F d, Y') }}</strong></p>
            @endif
            <p><strong>{{ trans('plugins/ecommerce::order.invoice') }}</strong> {{ $invoice->code }}</p>
        </td>
    </tr>
</table>

<table class="invoice-info-container">
    <tr>
        <td>
            @if (get_ecommerce_setting('company_name_for_invoicing') || get_ecommerce_setting('store_name'))
                <p>{{ get_ecommerce_setting('company_name_for_invoicing') ?: get_ecommerce_setting('store_name') }}</p>
            @endif

            @if (get_ecommerce_setting('company_address_for_invoicing'))
                <p>{{ get_ecommerce_setting('company_address_for_invoicing') }}</p>
            @else
                <p>{{ get_ecommerce_setting('store_address') }}, {{ get_ecommerce_setting('store_city') }}, {{ get_ecommerce_setting('store_state') }}, {{ EcommerceHelper::getCountryNameById(get_ecommerce_setting('store_country')) }}</p>
            @endif
            @if (get_ecommerce_setting('company_phone_for_invoicing') || get_ecommerce_setting('store_phone'))
                <p>{{ get_ecommerce_setting('company_phone_for_invoicing') ?: get_ecommerce_setting('store_phone') }}</p>
            @endif
            @if (get_ecommerce_setting('company_email_for_invoicing') || get_ecommerce_setting('store_email'))
                <p>{{ get_ecommerce_setting('company_email_for_invoicing') ?: get_ecommerce_setting('store_email') }}</p>
            @endif

            @if (get_ecommerce_setting('company_tax_id_for_invoicing') || get_ecommerce_setting('store_vat_number'))
                <p>{{ trans('plugins/ecommerce::ecommerce.setting.vat_number') }}: {{ get_ecommerce_setting('company_tax_id_for_invoicing') ?: get_ecommerce_setting('store_vat_number') }}</p>
            @endif
        </td>
        <td>
            @if ($invoice->customer_name)
                <p>{{ $invoice->customer_name }}</p>
            @endif
            @if ($invoice->customer_address )
                <p>{{ $invoice->customer_address }}</p>
            @endif

            @if ($invoice->customer_phone)
                <p>{{ $invoice->customer_phone }}</p>
            @endif
        </td>
    </tr>
</table>

@if ($invoice->description)
    <table class="invoice-info-container">
        <tr style="text-align: left">
            <td style="text-align: left">
                <p>{{ trans('plugins/ecommerce::order.note') }}: {{ $invoice->description }}</p>
            </td>
        </tr>
    </table>
@endif

<table class="line-items-container">
    <thead>
    <tr>
        <th class="heading-description">{{ trans('plugins/ecommerce::products.form.product') }}</th>
        <th class="heading-description">{{ trans('plugins/ecommerce::products.form.options') }}</th>
        <th class="heading-quantity">{{ trans('plugins/ecommerce::products.form.quantity') }}</th>
        <th class="heading-price">{{ trans('plugins/ecommerce::products.form.price') }}</th>
        <th class="heading-subtotal">{{ trans('plugins/ecommerce::products.form.total') }}</th>
    </tr>
    </thead>
    <tbody>

        @foreach ($invoice->items as $invoiceItem)
            @php
                $product = get_products([
                    'condition' => [
                        'ec_products.id' => $invoiceItem->reference_id,
                    ],
                    'take'   => 1,
                    'select' => [
                        'ec_products.id',
                        'ec_products.images',
                        'ec_products.name',
                        'ec_products.price',
                        'ec_products.sale_price',
                        'ec_products.sale_type',
                        'ec_products.start_date',
                        'ec_products.end_date',
                        'ec_products.sku',
                        'ec_products.is_variation',
                        'ec_products.status',
                        'ec_products.order',
                        'ec_products.created_at',
                    ],
                ]);
            @endphp
            @if (!empty($product))
                <tr>
                    <td>
                        {{ $product->original_product->name ?: $invoiceItem->name }}
                    </td>
                    <td>
                        <small>{{ $product->variation_attributes }}</small>

                        @if (!empty($invoiceItem->options) && is_array($invoiceItem->options))
                            @foreach($invoiceItem->options as $option)
                                @if (!empty($option['key']) && !empty($option['value']))
                                    <p class="mb-0">
                                        <small>{{ $option['key'] }}:
                                            <strong> {{ $option['value'] }}</strong></small>
                                    </p>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>
                        {{ $invoiceItem->qty }}
                    </td>
                    <td class="right">
                        {!! htmlentities(format_price($invoiceItem->sub_total)) !!}
                    </td>
                    <td class="bold">
                        {!! htmlentities(format_price($invoiceItem->amount)) !!}
                    </td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="4" class="right">
                {{ trans('plugins/ecommerce::products.form.sub_total') }}
            </td>
            <td class="bold">
                {!! htmlentities(format_price($invoice->sub_total)) !!}
            </td>
        </tr>
        @if (EcommerceHelper::isTaxEnabled())
            <tr>
                <td colspan="4" class="right">
                    {{ trans('plugins/ecommerce::products.form.tax') }}
                </td>
                <td class="bold">
                    {!! htmlentities(format_price($invoice->tax_amount)) !!}
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="4" class="right">
                {{ trans('plugins/ecommerce::products.form.shipping_fee') }}
            </td>
            <td class="bold">
                {!! htmlentities(format_price($invoice->shipping_amount)) !!}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="right">
                {{ trans('plugins/ecommerce::products.form.discount') }}
            </td>
            <td class="bold">
                {!! htmlentities(format_price($invoice->discount_amount)) !!}
            </td>
        </tr>
    </tbody>
</table>

<table class="line-items-container">
    <thead>
        <tr>
            <th>{{ trans('plugins/ecommerce::order.payment_info') }}</th>
            <th>{{ trans('plugins/ecommerce::order.total_amount') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="payment-info">

                @if ($invoice->payment->payment_channel->label())
                    <div>
                        {{ trans('plugins/ecommerce::order.payment_method') }}: <strong>{{ $invoice->payment->payment_channel->label() }}</strong>
                    </div>
                @endif

                @if ($invoice->payment->status->label())
                    <div>
                        {{ trans('plugins/ecommerce::order.payment_status_label') }}: <strong>{{ $invoice->payment->status->label() }}</strong>
                    </div>
                @endif

                @if ($invoice->payment->payment_channel == \Botble\Payment\Enums\PaymentMethodEnum::BANK_TRANSFER && $invoice->payment->status == \Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                    <div>
                        {{ trans('plugins/ecommerce::order.payment_info') }}: <strong>{!! BaseHelper::clean(get_payment_setting('description', $invoice->payment->payment_channel)) !!}</strong>
                    </div>
                @endif
            </td>
            <td class="large total">{!! htmlentities(format_price($invoice->amount)) !!}</td>
        </tr>
    </tbody>
</table>
</body>
</html>
