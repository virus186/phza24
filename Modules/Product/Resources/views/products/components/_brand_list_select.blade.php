<div class="primary_input mb-15">
    <div class="double_label d-flex justify-content-between">
        <label class="primary_input_label" for="">{{ __('product.brand') }}</label>
        <label class="primary_input_label green_input_label" for=""><a href="" id="add_new_brand">{{__('common.add_new')}}<i class="fas fa-plus-circle"></i></a></label>
    </div>
    <select  class="brand_id mb-15 primary_select2" id="brand_id" name="brand_id">
        <option selected disabled value="">{{__('common.select_one')}}</option>
        @if(old('brand_id'))
        @php
            $old_selected_brand = \DB::table('brands')->where('id', old('brand_id'))->first();
        @endphp
        <option value="{{$old_selected_brand->id}}" selected>{{$old_selected_brand->name}}</option>
        @elseif(isset($product))
            <option value="{{$product->brand_id}}" selected>{{$product->brand->name}}</option>
        @endif
    </select>
    <span class="text-danger">{{$errors->first('customer_id')}}</span>
</div>
