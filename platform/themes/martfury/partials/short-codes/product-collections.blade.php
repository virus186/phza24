@if (count($productCollections))
    <div class="ps-product-list mb-60">
        <product-collections-component title="{!! BaseHelper::clean($title) !!}" :product_collections="{{ json_encode($productCollections) }}" url="{{ route('public.ajax.products') }}" all="{{ route('public.products') }}"></product-collections-component>
    </div>
@endif
