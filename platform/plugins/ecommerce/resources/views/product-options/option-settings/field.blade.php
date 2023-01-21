@php
$value = $option['values'][0] ?? [];
@endphp
<div class="col-md-12 option-setting-tab" style="display: none" id="option-setting-field">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">{{ trans('plugins/ecommerce::product-option.price') }}</th>
            <th scope="col">{{ trans('plugins/ecommerce::product-option.price_type') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" name="affect_price" value="{{  $value['affect_price'] ?? 0 }}" placeholder="{{ trans('plugins/ecommerce::product-option.affect_price_label') }}" />
            </td>
            <td>
                <select class="form-control" name="affect_type" id="affect-type">
                    <option {{ isset($value['affect_type']) && ($value['affect_type']) == 0 ? 'selected' : '' }} value="0">{{ trans('plugins/ecommerce::product-option.fixed') }}</option>
                    <option {{ isset($value['affect_type']) && ($value['affect_type']) == 1 ? 'selected' : '' }} value="1">{{ trans('plugins/ecommerce::product-option.percent') }}</option>
                </select>
            </td>
        </tbody>
    </table>
</div>
