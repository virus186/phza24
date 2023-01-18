
@if ($data->stock_manage == 1)
    @php
        $stock = 0;
    @endphp
    @foreach ($data->skus as $sku)
        @php
            $stock += $sku->product_stock;
        @endphp
    @endforeach
@else
    @php
        $stock = 'Not Manage';
    @endphp
@endif

{{ $stock }}
@if ($data->unit_type_id != null)
    ({{ @$data->unit_type->name }})
@endif
