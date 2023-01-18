@if($data_type == 'product')
<div class="primary_input mb-25">
    <label class="primary_input_label" for="">{{ __('product.product_list') }}</label>
    <select name="data_id" id="slider_product" class="product mb-15">
        @if($first_product)
            <option value="{{$first_product->id}}" selected>{{$first_product->product->product_name }} @if(isModuleActive('MultiVendor')) [@if($first_product->seller->role->type == 'seller') {{$first_product->seller->first_name}} @else Inhouse @endif] @endif</option>
        @endif
        
    </select>
    <span class="text-danger"></span>
</div>

@elseif($data_type == 'category')
<div class="primary_input mb-25">
    <label class="primary_input_label" for="">{{ __('product.category_list') }}</label>
    <select name="data_id" id="slider_category" class="category mb-15">

        @if($first_category)
            @php
                $depth = '';
                for($i= 1; $i <= $first_category->depth_level; $i++){
                    $depth .='-';
                }
                $depth.='> ';
            @endphp
            <option value="{{$first_category->id}}" selected>{{$depth . @$first_category->name}}</option>
        @endif
        
    </select>
    <span class="text-danger"></span>
</div>
@elseif($data_type == 'brand')
<div class="primary_input mb-25">
    <label class="primary_input_label" for="">{{ __('product.brand_list') }}</label>
    <select name="data_id" id="slider_brand" class="slider_brand mb-15">
        @if($first_brand)
            <option value="{{$first_brand->id}}" selected>{{$first_brand->name}}</option>
        @endif
        
    </select>
    <span class="text-danger"></span>
</div>

@elseif($data_type == 'tag')
<div class="primary_input mb-25">
    <label class="primary_input_label" for="">{{ __('common.tag') }} {{__('common.list')}}</label>
    <select name="data_id" id="slider_tag" class=" slider_tag mb-15">
        @if($first_tag)
            <option value="{{$first_tag->id}}" selected>{{$first_tag->name}}</option>
        @endif
    </select>
    <span class="text-danger"></span>
</div>

@elseif($data_type == 'url')
<div class="col-lg-12">
    <div class="primary_input mb-25">
            <label class="primary_input_label"
                for="url">{{__('setup.url')}} <span class="text-danger">*</span></label>
                <input class="primary_input_field" type="text" id="url" name="data_id" autocomplete="off"
            value="" placeholder="{{__('setup.url')}}">
    </div>
    <span class="text-danger" id="error_name"></span>
</div>


@endif