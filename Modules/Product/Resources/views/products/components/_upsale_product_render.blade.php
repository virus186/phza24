<div class="table-responsive" id="product_list_div">
    <table class="table">
        <thead>
            <tr>
                <th width="10%" scope="col">
                    <label class="primary_checkbox d-flex ">
                        <input type="checkbox" id="upSaleAll">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <th width="20%" scope="col">{{ __('common.name') }}</th>
                <th width="15%" scope="col">{{ __('product.brand') }}</th>
                <th width="10%" scope="col">{{ __('product.thumbnail') }}</th>
                <th width="10%" scope="col">{{ __('product.created_at') }}</th>
            </tr>
        </thead>
        <tbody id="tablecontentsupSaleAll">
            @foreach ($products as $key => $item)
            <tr>
                <th scope="col">
                    <label class="primary_checkbox d-flex">
                        <input name="up_sale[]" id="up_sale_{{$key}}" @if(isset($product) && @$product->upSales->where('up_sale_product_id',$item->id)->first()) checked @endif value="{{$item->id}}" type="checkbox" class="upsale_product_checked">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <td>{{ $item->product_name }}</td>
                <td>{{ @$item->brand->name }}</td>
                <td>
                    <div class="product_img_div">
                        <img class="product_list_img" src="{{ showImage($item->thumbnail_image_source) }}" alt="{{ $item->product_name }}">
                    </div>
                </td>
                <td>{{ date(app('general_setting')->dateFormat->format, strtotime($item->created_at)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-container">
        {!! $products->links() !!}
    </div>
</div>