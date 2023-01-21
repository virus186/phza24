<div class="col-md-12 option-setting-tab" style="display: none" id="option-setting-multiple">
    <table class="table table-bordered setting-option">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{trans('plugins/ecommerce::product-option.label')}}</th>
            <th scope="col">{{ trans('plugins/ecommerce::product-option.price') }}</th>
            <th scope="col" colspan="2">{{ trans('plugins/ecommerce::product-option.price_type') }}</th>
        </tr>
        </thead>
        <tbody class="option-sortable">
        @if (!empty($option['values']))
            @foreach ($option['values'] as $key => $value)
                <tr class="option-row ui-state-default"  data-index="{{$key}}">
                    <input type="hidden" class="option-order" name="options[{{$key}}][order]" value="{{($value['order'] !== 9999) ? $value['order'] : $key}}">
                    <td class="text-center">
                        <i class="fa fa-sort"></i>
                    </td>
                    <td>
                        <input type="text" class="form-control option-label" name="options[{{$key}}][label]" value="{{$value['option_value']}}"
                               placeholder="{{ trans('plugins/ecommerce::product-option.label_placeholder') }}"/>
                    </td>
                    <td>
                        <input type="text" class="form-control affect_price" name="options[{{$key}}][affect_price]" value="{{$value['affect_price']}}"
                               placeholder="{{ trans('plugins/ecommerce::product-option.affect_price_label') }}"/>
                    </td>
                    <td>
                        <select class="form-control affect_type" name="options[{{$key}}][affect_type]">
                            <option {{($value["affect_type"]) == 0 ? "selected" : ''}} value="0">{{ trans('plugins/ecommerce::product-option.fixed') }}</option>
                            <option {{($value["affect_type"]) == 1 ? "selected" : ''}} value="1">{{ trans('plugins/ecommerce::product-option.percent') }}</option>
                        </select>
                    </td>
                    <td style="width: 50px">
                        <button class="btn btn-default remove-row" data-index="0"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="option-row" data-index="0">
                <td>
                    <input type="text" class="form-control option-label" name="options[0][label]" value=""
                           placeholder="{{ trans('plugins/ecommerce::product-option.label_placeholder') }}"/>
                </td>
                <td>
                    <input type="text" class="form-control affect_price" name="options[0][affect_price]" value=""
                           placeholder="{{ trans('plugins/ecommerce::product-option.affect_price_label') }}"/>
                </td>
                <td>
                    <select class="form-control affect_type" name="options[0][affect_type]">
                        <option value="0">{{ trans('plugins/ecommerce::product-option.fixed') }}</option>
                        <option value="1">{{ trans('plugins/ecommerce::product-option.percent') }}</option>
                    </select>
                </td>
                <td style="width: 50px">
                    <button class="btn btn-default remove-row" data-index="0"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <button type="button" class="btn btn-info mt-3 add-new-row" id="add-new-row">{{ trans('plugins/ecommerce::product-option.add_new_row') }}</button>
</div>
