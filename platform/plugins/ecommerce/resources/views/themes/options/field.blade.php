<div class="form-group option-field product-option-{{ Str::slug($option['name']) }} product-option-{{ $option['id'] }}"
     style="margin-bottom: 10px">
    <div class="row">
        <div class="col-4">
            <label class="{{ ($option['required']) ? 'required' : ''}}">
                {{ $option['name'] }}
            </label>
        </div>
        <div class="col-8">
            <div class="form-radio">
                @php
                    $price = 0;
                    if (!empty($value['affect_price']) && doubleval($value['affect_price']) > 0) {
                        $price = $value['affect_type'] == 0 ? $value['affect_price'] : (floatval($value['affect_price']) * $product->front_sale_price_with_taxes) / 100;
                    }
                @endphp
                <input type="hidden" name="options[{{ $option['id'] }}][option_type]" value="field" />
                <input data-extra-price="{{ $price }}" {{ ($option['required']) ? 'required' : '' }} type="text"
                       class="form-control" name="options[{{ $option['id'] }}][values]"
                       id="option-{{ $option['id'] }}-value-{{ Str::slug($option['values'][0]['option_value']) }}">
                <label for="option-{{ $option['id'] }}-value-{{ Str::slug($option['values'][0]['option_value']) }}">
                    @if ($price > 0)
                        <strong class="extra-price">+ {{ format_price($price) }}</strong>
                    @endif
                </label>
            </div>
        </div>
    </div>
</div>
