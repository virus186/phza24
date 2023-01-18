@foreach ($attributeLists as $key => $attribute)
    <div class="single_pro_categry">
        <h4 class="font_18 f_w_700">
        {{__('common.filter_by')}} {{ $attribute->name }}
        </h4>
        <ul class="Check_sidebar mb_35">
            @foreach ($attribute->values as $key => $attr_value)
                <li>
                    <label class="primary_checkbox d-flex">
                        <input type="checkbox" name="attr_value[]" class="getProductByChoice" data-id="{{ $attribute->id }}" data-value="{{ $attr_value->id }}" id="attr_value">
                        <span class="checkmark mr_10"></span>
                        <span class="label_name">{{ $attr_value->value }}</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach