<div class="note note-info" role="alert">
    <p class="mb-2 uppercase"><strong>{{ $title ?? __('You will receive money through the information below') }}</strong>:</p>
    @if (!$paymentChannel || $paymentChannel == \Botble\Marketplace\Enums\PayoutPaymentMethodsEnum::BANK_TRANSFER)
        @if (Arr::get($bankInfo, 'name'))
            <p>{{ __('Bank Name') }}: <strong>{{ Arr::get($bankInfo, 'name') }}</strong></p>
        @endif
        @if (Arr::get($bankInfo, 'code'))
            <p>{{ __('Bank Code/IFSC') }}: <strong>{{ Arr::get($bankInfo, 'code') }}</strong></p>
        @endif
        @if (Arr::get($bankInfo, 'full_name'))
            <p>{{ __('Account Holder Name') }}: <strong>{{ Arr::get($bankInfo, 'full_name') }}</strong></p>
        @endif
        @if (Arr::get($bankInfo, 'number'))
            <p>{{ __('Account Number') }}: <strong>{{ Arr::get($bankInfo, 'number') }}</strong></p>
        @endif
        @if (Arr::get($bankInfo, 'paypal_id'))
            <p>{{ __('PayPal ID') }}: <strong>{{ Arr::get($bankInfo, 'paypal_id') }}</strong></p>
        @endif
        @if (Arr::get($bankInfo, 'upi_id'))
            <p>{{ __('UPI ID') }}: <strong>{{ Arr::get($bankInfo, 'upi_id') }}</strong></p>
        @endif
        @if (Arr::get($bankInfo, 'description'))
            <p>{{ __('Description') }}: {{ Arr::get($bankInfo, 'description') }}</p>
        @endif
    @else
        @if (Arr::get($bankInfo, 'paypal_id'))
            <p>{{ __('PayPal Email ID') }}: <strong>{{ Arr::get($bankInfo, 'paypal_id') }}</strong></p>
        @endif
    @endif

    @isset($link)
        <p>{!! BaseHelper::clean(__('You can change it <a href=":link">here</a>', ['link' => $link])) !!}.</p>
    @endisset

    @if ($taxInfo && (Arr::get($taxInfo, 'business_name') || Arr::get($taxInfo, 'tax_id') || Arr::get($taxInfo, 'address')))
        <br>
        <p class="mb-2 uppercase"><strong>{{ __('Tax info') }}</strong>:</p>
        @if (Arr::get($taxInfo, 'business_name'))
            <p>{{ __('Business Name') }}: <strong>{{ Arr::get($taxInfo, 'business_name') }}</strong></p>
        @endif

        @if (Arr::get($taxInfo, 'tax_id'))
            <p>{{ __('Tax ID') }}: <strong>{{ Arr::get($taxInfo, 'tax_id') }}</strong></p>
        @endif

        @if (Arr::get($taxInfo, 'address'))
            <p>{{ __('Address') }}: <strong>{{ Arr::get($taxInfo, 'address') }}</strong></p>
        @endif
    @endif
</div>
