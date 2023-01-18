@if(count($combinations[0]) > 0)
    <table class="table table-bordered sku_table">
        <thead>
        <tr>
            <td width="10%" class="text-center">
                <label for="" class="control-label">{{__('product.variant')}}</label>
            </td>

            <td width="13%" class="text-center">
                <label for="" class="control-label">{{__('product.selling_price')}}</label>
            </td>
            @if(!isModuleActive('MultiVendor'))
                <td width="13%" class="text-center stock_td d-none">
                    <label for="" class="control-label">{{__('product.stock')}}</label>
                </td>
            @endif
            <td width="11%" class="text-center">
                <label for="" class="control-label">{{__('product.SKU')}}</label>
            </td>
            <td width="20%" class="text-center variant_physical_div">
                <label for="" class="control-label">{{__('common.image')}} (165x165)PX</label>
            </td>
            <td width="15%" class="text-center variant_digital_div">
                <label for="" class="control-label">{{__('product.file')}}</label>
            </td>
        </tr>
        </thead>
        <tbody>


        @php
            $valIncre = 1;
            $imgIncrement = 0;
        @endphp

        @foreach ($combinations as $key => $combination)
            @php
                $sku = '';
                $ttt = $key;
                foreach (explode(' ', $product_name) as $key => $value) {
                    $sku .= substr($value, 0, 1);
                }

                $str = '';
                $str_id = '';
                $attribute_id = '';
                foreach ($combination as $key => $items){
                    $item_value = \Modules\Product\Entities\AttributeValue::find($items);
                    if ($item_value->attribute_id == 1) {
                        $item = $item_value->color->name;
                    }else {
                        $item = $item_value->value;
                    }

                    if($key > 0 ){
                        $attribute_id .= '-'.str_replace(' ', '', $attribute[$key]);
                        $str_id .= '-'.str_replace(' ', '', $items);
                        $str .= '-'.str_replace(' ', '', $item);
                        $sku .='-'.str_replace(' ', '', $item);
                    }else {
                        $attribute_id .= str_replace(' ', '', $attribute[$key]);
                        $str_id .= str_replace(' ', '', $items);
                        $str .= str_replace(' ', '', $item);
                        $sku .= '-'.str_replace(' ', '', $item);
                    }
                }

                $valIncre +=$valIncre;
                $imgIncrement += 1;

            @endphp
            @if(strlen($str) > 0)
                <tr class="variant">
                    <td class="text-center">
                        <input type="hidden" name="str_attribute_id[]" value="{{ $attribute_id }}">
                        <input type="hidden" name="str_id[]" value="{{ $str_id }}">
                        <label for="" class="control-label mt-30">{{ $str }}</label>

                    </td>

                    <td class="text-center">
                        <div class="float-left mt_10">
                            <input class="mt-1 selling_price style_selling_price_field" type="number" name="selling_price_sku[]"
                                   value="{{ gv($selling_price_sku,$ttt, 0) }}" min="0" step="{{step_decimal()}}"
                                   class="form-control" required>
                            @if (isModuleActive('WholeSale'))
                                <button type="button" data-toggle="modal" tabindex="-1"
                                        data-target="#variant_wholesale_price_modal_{{$sku.$ttt}}"
                                        class="btn btn-sm style_plus_icon add_variant_whole_sale_price">
                                    <i class="ti-plus"></i>
                                </button>
                                <!-- Append WholeSale Price  -->
                                <ul id="append_w_p{{$sku.$ttt}}"></ul>
                            @endif
                        </div>
                    </td>

                    @if(!isModuleActive('MultiVendor'))
                        <td class="text-center pt-20 stock_td d-none">
                            <input class="primary_input_field mt-20" type="number" name="sku_stock[]"
                                   value="{{gv($sku_stock,$ttt, 0)}}" min="0" step="0" class="form-control" required>
                        </td>
                    @endif
                    <td class="text-center pt-25">
                        <input class="primary_input_field mt-20" type="text" name="sku[]"
                               value="{{ gv($old_sku,$ttt, $sku) }}" class="form-control">
                        <input type="hidden" name="track_sku[]" value="{{ $sku }}">
                    </td>

                    <td class="variant_physical_div text-center pt_2">
                        <div class="primary_input ">
                            <div class="primary_file_uploader mt-25" data-toggle="amazuploader" data-multiple="false"
                                 data-type="image" data-name="variant_image_{{$imgIncrement}}">
                                <input class="primary-input file_amount" type="text"
                                       id="variant_image_file_{{$valIncre}}" placeholder="{{__("common.image")}}"
                                       readonly="">
                                <button class="" type="button">
                                    <label class="primary-btn small fix-gr-bg"
                                           for="variant_image_{{$valIncre}}">{{__("product.Browse")}} </label>

                                    <input type="hidden" class="selected_files" value="">
                                </button>
                            </div>
                            <div class="product_image_all_div variant_image">

                            </div>
                        </div>
                    </td>
                    <td class="variant_digital_div text-center">
                        <div class="primary_input mb-10">
                            <div class="primary_file_uploader">
                                <input class="primary-input mt_10" type="text" id="variant_digital_file_{{$valIncre}}"
                                       placeholder="{{__("common.file")}}" readonly="">
                                <button class="mt_10" type="button">
                                    <label class="primary-btn small fix-gr-bg"
                                           for="digital_file_{{$valIncre}}">{{__("product.Browse")}} </label>
                                    <input type="file" class="d-none variant_digital_file_change" name="digital_file[]"
                                           data-name_id="variant_digital_file_{{$valIncre}}"
                                           id="digital_file_{{$valIncre}}">
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif
            @if(isModuleActive('WholeSale'))
                @include('wholesale::components._create_variant_wholesale_price_modal', ['modalTargetId'=> $sku.$ttt, 'incKey'=>$ttt])
            @endif
        @endforeach
        </tbody>
    </table>
@endif
