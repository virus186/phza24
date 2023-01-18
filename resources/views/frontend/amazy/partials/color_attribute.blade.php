@if ($color != null)
    <div class="single_pro_categry">
        <h4 class="font_18 f_w_700">
            {{__('common.filter_by')}} {{ $color->name }}
        </h4>
        <div class="color_filter">
            @foreach ($color->values as $k => $color_name)
                <div class="single_coulorFilter">
                    <label class="round_checkbox black_check d-flex">
                        <input type="radio" id="radio-{{$k}}" name="color[]" color="color" data-id="{{ $color->id }}" data-value="{{ $color_name->id }}" class="attr_val_name attr_clr getProductByChoice" value="{{ $color_name->color->name }}">
                        <span class="checkmark colors_{{$k}}"></span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif
<script type="text/javascript">
    $(document).ready(function(){
        '@if ($color != null)'+
            '@foreach ($color->values as $ki => $item)'+
                $(".colors_{{$ki}}").css("background-color", "{{ $item->value }}");
            '@endforeach'+
        '@endif'
    });
</script>