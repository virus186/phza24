<div class="primary_input mb-25">
    <label class="primary_input_label" for="">{{ __('product.parent_category') }} <span class="text-danger">*</span></label>
    <select class="primary_select mb-25" name="parent_id" id="parent_id">
        @if(isset($first_category) && $first_category != null)
            <option value="{{$first_category->id}}" selected>{{$first_category->name}}</option>
        @endif
    </select>
    
    <span class="text-danger"></span>
    
</div>