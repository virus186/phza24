<a href="{{ route('ecommerce.invoice.generate-invoice', ['id' => $invoice->id, 'type' => 'print']) }}" target="_blank" class="btn btn-success my-2">
    {{ trans('plugins/ecommerce::invoice.print')}}
</a>

<a href="{{ route('ecommerce.invoice.generate-invoice', ['id' => $invoice->id, 'type' => 'download']) }}" target="_blank" class="btn btn-danger my-2">
    {{ trans('plugins/ecommerce::invoice.download')}}
</a>
