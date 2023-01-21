<li class="list-group-item">
    <input
        class="magic-radio shipping_method_input"
        type="radio"
        name="shipping_method[{{ $storeId }}]"
        id="{{ "shipping-method-$storeId-$shippingKey-$shippingOption" }}"
        @if (old('shipping_method.' . $storeId, $shippingKey) == $defaultShippingMethod &&
            old('shipping_option.' . $storeId, $shippingOption) == $defaultShippingOption) checked @endif
        value="{{ $shippingKey }}"
        data-option="{{ $shippingOption }}"
        data-id="{{ $storeId }}">
    <label for="{{ "shipping-method-$storeId-$shippingKey-$shippingOption" }}">
        <div>
            @if ($image = Arr::get($shippingItem, 'image'))
                <img src="{{ $image }}" alt="{{ $shippingItem['name'] }}" style="max-height: 40px; max-width: 55px">
            @endif
            <span>
                {{ $shippingItem['name'] }} - 
                @if ($shippingItem['price'] > 0)
                    {{ format_price($shippingItem['price']) }}
                @else
                    <strong>{{ __('Free shipping') }}</strong>
                @endif
            </span>
        </div>
        @if ($description = Arr::get($shippingItem, 'description'))
            <div>
                <small class="text-secondary">{!! BaseHelper::clean($description) !!}</small>
            </div>
        @endif
    </label>
</li>
