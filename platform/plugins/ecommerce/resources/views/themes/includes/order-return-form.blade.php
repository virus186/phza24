@if ($order)
    <div class="customer-order-detail">
        <div class="row">
            <div class="col-md-6">
                <h5>{{ __('Order information') }}</h5>
                <p>
                    <span>{{ __('Order number') }}: </span>
                    <strong>{{ $order->code }}</strong>
                </p>
                <p>
                    <span>{{ __('Time') }}: </span>
                    <strong>{{ $order->created_at->format('h:m d/m/Y') }}</strong>
                </p>
            </div>
            <div class="col-md-6">
                <p>
                    <span>{{ __('Completed at') }}: </span>
                    <strong class="text-info">{{ $order->completed_at->format('h:m d/m/Y') }}</strong>
                </p>
                <p>
                    <span>{{ __('Shipment Status') }}: </span>
                    <strong class="text-info">{{ $order->shipment->status->label() }}</strong>
                </p>
                <p>
                    <span>{{ __('Payment status') }}: </span>
                    <strong class="text-info">{{ $order->payment->status->label() }}</strong>
                </p>
            </div>

        </div>
        <br/>
        {!! Form::open(['url' => route('customer.order_returns.send_request'), 'method' => 'POST']) !!}
        {!! Form::hidden('order_id', $order->id) !!}

        @if (!EcommerceHelper::allowPartialReturn())
            <div class="col-sm-6 col-md-3 form-group">
                <label for="reason" class="col-form-label"><strong>{{ __('Return Reason') }}:</strong></label>
                {!! Form::select('reason', \Botble\Ecommerce\Enums\OrderReturnReasonEnum::labels(), old('reason'), ['class' => 'form-control form-select', 'placeholder' => __('Choose Reason')]) !!}
            </div>
            <br/>
        @endif

        <h5>{{ __('Choose products') }}</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th>{{ __('Product') }}</th>
                            @if(EcommerceHelper::allowPartialReturn())
                                <th class="text-center" style="width: 100px;">{{ __('Quantity') }}</th>
                                <th class="text-end">{{ __('Reason') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($order->products as $key => $orderProduct)
                            @php
                                $product = get_products([
                                    'condition' => [
                                        'ec_products.id' => $orderProduct->product_id,
                                    ],
                                    'take' => 1,
                                    'select' => [
                                        'ec_products.id',
                                        'ec_products.images',
                                        'ec_products.name',
                                        'ec_products.price',
                                        'ec_products.sale_price',
                                        'ec_products.sale_type',
                                        'ec_products.start_date',
                                        'ec_products.end_date',
                                        'ec_products.sku',
                                        'ec_products.is_variation',
                                        'ec_products.status',
                                        'ec_products.order',
                                        'ec_products.created_at',
                                    ],
                                ]);
                            @endphp
                            <tr>
                                <td class="text-center">
                                    {!! Form::checkbox('return_items[' . $key . '][is_return]', 'checked', true) !!}
                                    <input hidden name="return_items[{{ $key }}][order_item_id]"
                                           value="{{ $orderProduct->id }}"></td>
                                <td class="text-center">
                                    <img
                                        src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                        alt="{{ $orderProduct->product_name }}" width="50">
                                </td>
                                <td>
                                    {{ $orderProduct->product_name }} @if ($product && $product->sku)
                                        ({{ $product->sku }})
                                    @endif
                                    @if ($product && $product->is_variation)
                                        <p>
                                            <small>{{ $product->variation_attributes }}</small>
                                        </p>
                                    @endif
                                </td>
                                @if (EcommerceHelper::allowPartialReturn())
                                    <td class="product-quantity product-md d-md-table-cell d-block"
                                        data-title="Quantity">
                                        <div class="product-button">
                                            <div class="quantity">
                                                <label class="label-quantity">{{ __('Quantity') }}:</label>
                                                <div class="qty-box">
                                                    <span class="svg-icon decrease">
                                                        <svg aria-hidden="true" role="img" focusable="false" viewBox="0 0 32 32"><path d="M29.6 17.6h-28.8c-0.442 0-0.8-0.358-0.8-0.8s0.358-0.8 0.8-0.8h28.8c0.442 0 0.8 0.358 0.8 0.8s-0.358 0.8-0.8 0.8z"></path></svg>
                                                    </span>

                                                    {!! Form::input('number', 'return_items[' . $key . '][qty]', $orderProduct->qty, ['class' =>
                                                    'input-text qty',
                                                    'min' => 1, 'max' => $orderProduct->qty, 'step' => 1]) !!}

                                                    <span class="svg-icon increase">
                                                        <svg aria-hidden="true" role="img" focusable="false" viewBox="0 0 32 32"><path d="M29.6 16h-13.6v-13.6c0-0.442-0.358-0.8-0.8-0.8s-0.8 0.358-0.8 0.8v13.6h-13.6c-0.442 0-0.8 0.358-0.8 0.8s0.358 0.8 0.8 0.8h13.6v13.6c0 0.442 0.358 0.8 0.8 0.8s0.8-0.358 0.8-0.8v-13.6h13.6c0.442 0 0.8-0.358 0.8-0.8s-0.358-0.8-0.8-0.8z"></path></svg>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {!! Form::select('return_items[' . $key . '][reason]', \Botble\Ecommerce\Enums\OrderReturnReasonEnum::labels(), '', ['class' => 'form-control form-select', 'placeholder' => __('Choose Reason')]) !!}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
            <div class="col-md-12 pt-3">
                @if ($order->canBeReturned())
                    {!! Form::submit(__('Submit Return Request'), ['class' => 'btn btn-lg btn-danger']) !!}
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@elseif (request()->input('order_id') || request()->input('email'))
    <p class="text-center text-danger">{{ __('Order not found!') }}</p>
@endif
