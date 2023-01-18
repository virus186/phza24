@extends('frontend.amazy.layouts.app')
@push('styles')
    <style>
        .amaz_select3 {
            min-width: 220px;
        }
    </style>
@endpush
@section('content')
    <div class="amazy_dashboard_area dashboard_bg section_spacing6">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    @include('frontend.amazy.pages.profile.partials._menu')
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="dashboard_white_box_header d-flex align-items-center gap_20  mb_20">
                        <h3 class="font_20 f_w_700 mb-0 ">{{__('amazy.Purchase History')}}</h3>
                        <select class="amaz_select3" id="filter_order">
                            <option value="all" @if(!session()->has('purchase_history_filter') || session()->get('purchase_history_filter') == 'all') selected @endif>{{__('amazy.All History')}}</option>
                            <option value="pending" @if(session()->has('purchase_history_filter') && session()->get('purchase_history_filter') == 'pending') selected @endif>{{__('order.pending_orders')}}</option>
                            <option value="confirm" @if(session()->has('purchase_history_filter') && session()->get('purchase_history_filter') == 'confirm') selected @endif>{{__('order.confirmed_orders')}}</option>
                            <option value="complete" @if(session()->has('purchase_history_filter') && session()->get('purchase_history_filter') == 'complete') selected @endif>{{__('order.completed_orders')}}</option>
                            <option value="cancel" @if(session()->has('purchase_history_filter') && session()->get('purchase_history_filter') == 'cancel') selected @endif>{{__('order.cancelled_orders')}}</option>
                        </select>
                    </div>
                    <div class="dashboard_white_box bg-white mb_25 pt-0 ">
                        <div class="dashboard_white_box_body">
                            <div class="table-responsive mb_30">
                                <table class="table amazy_table2 mb-0">
                                    <thead>
                                        <tr>
                                            <th class="font_14 f_w_700" scope="col">{{__('common.details')}}</th>
                                            <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('common.amount')}}</th>
                                            <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('order.delivery_status')}}</th>
                                            <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('order.payment_status')}}</th>
                                            <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{__('common.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $key => $order)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <h4 class="font_16 f_w_700  ">{{__('common.order')}}: {{@$order->order->order_number}}</h4>
                                                        @if(isModuleActive('MultiVendor'))
                                                            <h4 class="font_14 f_w_600  ">{{__('common.package')}}: {{@$order->package_code}}</h4>
                                                        @endif
                                                        <p class="font_14 f_w_500 mb-0 lh-1">{{date(app('general_setting')->dateFormat->format, strtotime($order->created_at))}}</p>
                                                    </div>
                                                
                                                </td>
                                                <td>
                                                    @php
                                                        $total_price = $order->products->sum('total_price') + $order->shipping_cost + $order->tax_amount;
                                                    @endphp
                                                    <h4 class="font_16 f_w_500 m-0 text-nowrap">{{single_price($total_price)}}</h4>
                                                </td>
                                                <td>
                                                    
                                                    @if($order->is_cancelled)
                                                        <a class="table_badge_btn style_5 text-nowrap">{{__('common.cancelled')}}</a>
                                                    @elseif($order->delivery_status == 1)
                                                        <a class="table_badge_btn style3 text-nowrap">{{__('common.pending')}}</a>
                                                    @elseif($order->delivery_status == 2)
                                                        <a class="table_badge_btn text-nowrap">{{__('defaultTheme.processing')}}</a>
                                                    @elseif($order->delivery_status == 3)
                                                        <a class="table_badge_btn text-nowrap">{{__('common.shipped')}}</a>
                                                    @elseif($order->delivery_status == 4)
                                                        <a class="table_badge_btn text-nowrap">{{__('amazy.Received')}}</a>
                                                    @elseif($order->delivery_status >= 5)
                                                        <a class="table_badge_btn style4 text-nowrap">{{$order->delivery_process->name}}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($order->is_paid || $order->delivery_status >= 5)
                                                        <a class="table_badge_btn style4 text-nowrap">{{__('common.paid')}}</a>
                                                    @else
                                                        <a class="table_badge_btn style3 text-nowrap">{{__('common.pending')}}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="amazy_status_btns d-flex gap_5 align-items-center">
                                                        <button class="amazy_status_btn purchase_show" data-id="{{$order->id}}">
                                                            <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="11.5" viewBox="0 0 16 11.5">
                                                                <path  data-name="Path 4189" d="M15.333,124.168H.667a.755.755,0,0,1,0-1.5H15.333a.755.755,0,0,1,0,1.5Zm0,0" transform="translate(0 -117.668)" fill="#fd4949"/>
                                                                <path  data-name="Path 4190" d="M15.333,1.5H.667A.712.712,0,0,1,0,.75.712.712,0,0,1,.667,0H15.333A.712.712,0,0,1,16,.75.712.712,0,0,1,15.333,1.5Zm0,0" fill="#fd4949"/>
                                                                <path  data-name="Path 4191" d="M15.333,246.832H.667a.755.755,0,0,1,0-1.5H15.333a.755.755,0,0,1,0,1.5Zm0,0" transform="translate(0 -235.332)" fill="#fd4949"/>
                                                            </svg>
                                                        </button>
                                                        <a href="{{ route('frontend.my_purchase_order_pdf', encrypt($order->order->id)) }}" target="_blank" class="amazy_status_btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="9.333" height="14" viewBox="0 0 9.333 14">
                                                                <g  data-name="download (1)" transform="translate(-85.334 0)">
                                                                    <g  data-name="Group 3491" transform="translate(85.334 0)">
                                                                    <g  data-name="Group 3490">
                                                                        <path  data-name="Path 4187" d="M89.588,11.493h0c.013.013.028.026.042.038l.021.016.025.018.025.015.023.014.027.013.025.012.026.01.028.01.026.007.029.007.031,0,.026,0a.587.587,0,0,0,.115,0l.026,0,.031,0,.029-.007.026-.007.028-.01.026-.01.025-.012.027-.013.023-.014.025-.015.025-.018.021-.016q.022-.018.042-.038h0L94.5,7.41a.583.583,0,0,0-.825-.825L90.584,9.672V.583a.583.583,0,0,0-1.167,0V9.672L86.33,6.586a.583.583,0,0,0-.825.825Z" transform="translate(-85.334)" fill="#fd4949"/>
                                                                        <path  data-name="Path 4188" d="M94.084,469.333H85.917a.584.584,0,0,0,0,1.168h8.167a.584.584,0,0,0,0-1.168Z" transform="translate(-85.334 -456.501)" fill="#fd4949"/>
                                                                    </g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($orders->lastPage() > 1)
                                <x-pagination-component :items="$orders" type=""/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal_div"></div>
@endsection

@push('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('change', '#filter_order', function(){
                    let fil_value = $(this).val();
                    let url = "{{url()->current()}}" + '?filter='+fil_value;
                    $('#pre-loader').show();
                    location.replace(url)
                });
                $(document).on('click', '.page_link', function(event){
                    event.preventDefault();
                    let current_page = $(this).attr('href');
                    let fil_value = $('#filter_order').val();
                    let url = current_page + '&filter='+fil_value;
                    $('#pre-loader').show();
                    location.replace(url)
                });

                $(document).on('click', '.purchase_show', function(event){
                    let id = $(this).data('id');
                    let data = {
                        _token: "{{csrf_token()}}",
                        order_id: id
                    }
                    $('#pre-loader').show();
                    $.post("{{route('frontend.my_purchase_history_modal')}}",data, function(response){
                        $('#modal_div').html(response);
                        $('#purchase_history_modal').modal('show');
                        $('#pre-loader').hide();
                    });
                });
            });
        })(jQuery);
    </script>
@endpush