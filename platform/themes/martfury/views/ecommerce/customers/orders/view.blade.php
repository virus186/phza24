@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')
@section('content')
    @php Theme::set('pageName', __('Order information')) @endphp
    <div class="card">
        <div class="card-header">
            <h3>{{ __('Order information') }}</h3>
        </div>
        <div class="card-body">
            <div class="customer-order-detail">
                <div class="row">
                    <div class="col-md-6">
                        <div class="order-slogan">
                            @php
                                $logo = theme_option('logo_in_the_checkout_page') ?: theme_option('logo');
                            @endphp
                            <img width="100" src="{{ RvMedia::getImageUrl($logo) }}"
                                 alt="{{ theme_option('site_title') }}">
                            <br/>
                            {{ setting('contact_address') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="order-meta">
                            <p><span>{{ __('Order number') }}:</span> <span
                                    class="order-detail-value">{{ get_order_code($order->id) }}</span></p>
                            <span>{{ __('Time') }}:</span> <span
                                class="order-detail-value">{{ $order->created_at->translatedFormat('M d, Y h:m') }}</span>
                        </div>
                    </div>
                </div>

                <h5>{{ __('Order information') }}</h5>
                <div class="col-12">
                    <span>{{ __('Order status') }}:</span> <span
                        class="order-detail-value">{!! $order->status->toHtml() !!}</span>
                </div>

                <div class="col-12">
                    <span>{{ __('Payment method') }}:</span> <span
                        class="order-detail-value"> {!! $order->payment->payment_channel->label() !!} </span>
                    <br>
                    <span>{{ __('Payment status') }}:</span> <span
                        class="order-detail-value">{!! $order->payment->status->toHtml() !!}</span>
                </div>

                <div class="col-12">
                    <span>{{ __('Amount') }}:</span> <span
                        class="order-detail-value"> {{ format_price($order->amount) }} </span>
                </div>

                @if (EcommerceHelper::isTaxEnabled())
                    <div class="col-12">
                        <span>{{ __('Tax') }}:</span> <span
                            class="order-detail-value"> {{ format_price($order->tax_amount) }} </span>
                    </div>
                @endif

                <div class="col-12">
                    <span>{{ __('Shipping fee') }}:</span> <span
                        class="order-detail-value">  {{ format_price($order->shipping_amount) }} </span>
                </div>

                <div class="col-12">
                    @if ($order->description)
                        <span>{{ __('Note') }}:</span> <span class="order-detail-value text-warning">{{ $order->description }} </span>&nbsp;
                    @endif
                </div>
                <br>
                <h5>{{ __('Customer information') }}</h5>

                <div class="col-12">
                    <span>{{ __('Full Name') }}:</span> <span class="order-detail-value">{{ $order->address->name }} </span>
                </div>

                <div class="col-12">
                    <span>{{ __('Phone') }}:</span> <span class="order-detail-value">{{ $order->address->phone }} </span>
                </div>

                <div class="col-12">
                    <span>{{ __('Address') }}:</span> <span
                        class="order-detail-value"> {{ $order->address->address }} </span>
                </div>

                <div class="col-12">
                    <span>{{ __('City') }}:</span> <span
                        class="order-detail-value">{{ $order->address->city_name }} </span>
                </div>
                <div class="col-12">
                    <span>{{ __('State') }}:</span> <span
                        class="order-detail-value"> {{ $order->address->state_name }} </span>
                </div>
                <div class="col-12">
                    <span>{{ __('Country') }}:</span> <span
                        class="order-detail-value"> {{ $order->address->country_name }} </span>
                </div>
                <br>
                <h5>{{ __('Order detail') }}</h5>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">{{ __('Image') }}</th>
                                <th>{{ __('Product') }}</th>
                                <th class="text-center">{{ __('Amount') }}</th>
                                <th class="text-right" style="width: 100px">{{ __('Quantity') }}</th>
                                <th class="price text-right">{{ __('Total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->products as $key => $orderProduct)
                                @php
                                    $product = get_products([
                                    'condition' => [
                                        'ec_products.id' => $orderProduct->product_id,
                                    ],
                                    'take'   => 1,
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
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">
                                        <img src="{{ RvMedia::getImageUrl($product ? $product->image : null, 'thumb', false, RvMedia::getDefaultImage()) }}" width="50" alt="{{ $orderProduct->product_name }}">
                                    </td>
                                    <td>
                                        {{ $orderProduct->product_name }} @if ($product && $product->sku) ({{ $product->sku }}) @endif
                                        @if ($product && $product->is_variation)
                                            <p class="mb-0">
                                                <small>{{ $product->variation_attributes }}</small>
                                            </p>
                                        @endif

                                        @if (!empty($orderProduct->options) && is_array($orderProduct->options))
                                            @foreach($orderProduct->options as $option)
                                                @if (!empty($option['key']) && !empty($option['value']))
                                                    <p class="mb-0"><small>{{ $option['key'] }}: <strong> {{ $option['value'] }}</strong></small></p>
                                                @endif
                                            @endforeach
                                        @endif

                                        @if (is_plugin_active('marketplace') && $product && $product->original_product->store->id)
                                            <p class="d-block mb-0 sold-by">
                                                <small>{{ __('Sold by') }}: <a href="{{ $product->original_product->store->url }}">{{ $product->original_product->store->name }}</a>
                                                </small>
                                            </p>
                                        @endif
                                    </td>
                                    <td>{{ format_price($orderProduct->price) }}</td>
                                    <td class="text-center">{{ $orderProduct->qty }}</td>
                                    <td class="money text-right">
                                        <strong>
                                            {{ format_price($orderProduct->price * $orderProduct->qty) }}
                                        </strong>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($order->shipment->id)
                    <br>
                    <h5>{{ __('Shipping Information:') }}</h5>
                    <p><span class="d-inline-block">{{ __('Shipping Status') }}</span>: <strong class="d-inline-block text-info">{!! BaseHelper::clean($order->shipment->status->toHtml()) !!}</strong></p>
                    @if ($order->shipment->shipping_company_name)
                        <p><span class="d-inline-block">{{ __('Shipping Company Name') }}</span>: <strong class="d-inline-block">{{ $order->shipment->shipping_company_name }}</strong></p>
                    @endif
                    @if ($order->shipment->tracking_id)
                        <p><span class="d-inline-block">{{ __('Tracking ID') }}</span>: <strong class="d-inline-block">{{ $order->shipment->tracking_id }}</strong></p>
                    @endif
                    @if ($order->shipment->tracking_link)
                        <p><span class="d-inline-block">{{ __('Tracking Link') }}</span>: <a
                                    href="{{ $order->shipment->tracking_link }}" target="_blank"><strong class="d-inline-block">{{ $order->shipment->tracking_link }}</strong></a></p>
                    @endif
                    @if ($order->shipment->note)
                        <p><span class="d-inline-block">{{ __('Delivery Notes') }}</span>: <strong class="d-inline-block">{{ $order->shipment->note }}</strong></p>
                    @endif
                    @if ($order->shipment->estimate_date_shipped)
                        <p><span class="d-inline-block">{{ __('Estimate Date Shipped') }}</span>: <strong class="d-inline-block">{{ $order->shipment->estimate_date_shipped }}</strong></p>
                    @endif
                    @if ($order->shipment->date_shipped)
                        <p><span class="d-inline-block">{{ __('Date Shipped') }}</span>: <strong class="d-inline-block">{{ $order->shipment->date_shipped }}</strong></p>
                    @endif
                @endif

                <br>
                <div>
                    @if ($order->isInvoiceAvailable())
                        <a href="{{ route('customer.print-order', $order->id) }}" class="ps-btn ps-btn--sm mr-10"><i class="fa fa-download"></i> {{ __('Download invoice') }}</a>
                    @endif
                    @if ($order->canBeCanceled())
                        <a href="{{ route('customer.orders.cancel', $order->id) }}" onclick="return confirm('{{ __('Are you sure?') }}')" class="ps-btn ps-btn--sm ps-btn--danger">{{ __('Cancel order') }}</a>
                    @endif
                    @if ($order->canBeReturned())
                        <a href="{{ route('customer.order_returns.request_view', $order->id) }}"
                           class="ps-btn ps-btn--sm ps-btn--danger">
                            {{ __('Return Product(s)') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
