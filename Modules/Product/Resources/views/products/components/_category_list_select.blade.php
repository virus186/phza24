
<div class="primary_input mb-25">
    <div class="double_label d-flex justify-content-between">
        <label class="primary_input_label" for="">{{ __('product.category') }} <span class="text-danger">*</span></label>
        <label class="primary_input_label green_input_label" for=""><a href="" id="add_new_category">{{__('common.add_new')}}<i class="fas fa-plus-circle"></i></a></label>
    </div>
    <select name="category_ids[]" id="category_id" class="mb-15 category_id" @if(app('general_setting')->multi_category == 1) multiple @elseif(isset($product) && count($product->categories) > 1) multiple @endif required="1">
        @if(old('category_ids'))
            @php
                $old_categories = \DB::table('categories')->whereRaw("id in ('". implode("','",old('category_ids'))."')")->get();
            @endphp
            @foreach($old_categories as $category)
                <option value="{{$category->id}}" selected>{{$category->name}}</option>
            @endforeach
        @elseif(isset($product_categories))
            @foreach($product_categories as $category)
                <option value="{{$category->id}}" selected>{{$category->name}}</option>
            @endforeach
        @endif
    </select>
    <span class="text-danger" id="error_category_ids">{{ $errors->first('category_id') }}</span>
</div>
