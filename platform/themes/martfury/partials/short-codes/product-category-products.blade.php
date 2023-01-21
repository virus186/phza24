@if ($category)
    <div class="ps-product-list">
        <product-category-products-component limit="{{ $limit }}" :category="{{ json_encode($category) }}" :children="{{ json_encode($category->activeChildren) }}" url="{{ route('public.ajax.product-category-products') }}" all="{{ $category->url }}"></product-category-products-component>
    </div>
@endif
