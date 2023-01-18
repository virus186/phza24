@extends('backEnd.master')
@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('modules/paymentgateway/css/style.css'))}}" />


@endsection
@section('mainContent')
    <div class="row">
        <div class="col-md-5 col-sm-6 col-xs-12">
            <div class="main-title mb-25 d-md-flex">
                <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('general_settings.Activation') }}</h3>
                @if(isModuleActive('MultiVendor') && auth()->user()->role->type != 'seller')
                    <ul class="d-flex">
                        <li><a class="primary-btn radius_30px mr-10 fix-gr-bg" href="{{route("payment_gateway.setting")}}"><i class="fas fa-cog"></i>{{__('payment_gatways.Global Configuration')}}</a></li>
                    </ul>
                @endif
            </div>

            <div class="common_QA_section QA_section_heading_custom">
                <div class="QA_table ">
                    <!-- table-responsive -->
                    <div class="">
                        <table class="table Crm_table_active2">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('common.sl')}}</th>
                                    <th scope="col">{{ __('common.name') }}</th>
                                    <th scope="col" class="text-right">{{ __('general_settings.activate') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gateway_activations as $key => $gateway_activation)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            {{ strtoupper(str_replace("_"," ",$gateway_activation->method->method)) }}
                                            @if(config('app.sync'))
                                                @if($gateway_activation->method->method == 'Bkash' || $gateway_activation->method->method == 'Mercado Pago' || $gateway_activation->method->method == 'SslCommerz')
                                                    <span class="demo_addons">Addon</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <label class="switch_toggle" for="checkbox{{ $gateway_activation->id }}">
                                                <input type="checkbox" id="checkbox{{ $gateway_activation->id }}" @if ($gateway_activation->status == 1) checked @endif @if (permissionCheck('update_payment_activation_status')) value="{{ $gateway_activation->id }}" class="payment_gateways_activate" @else disabled @endif>
                                                <div class="slider round"></div>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-6 col-xs-12">
            <section class="admin-visitor-area up_st_admin_visitor">
                <div class="container-fluid p-0">
                    <div class="row" id="form_list_div">
                        @include('paymentgateway::components._all_config_form_list', [$gateway_activations])
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
    (function($){

        "use strict";
        $(document).ready(function(){
            $(document).on('change','.payment_gateways_activate', function(){
                if(this.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $('#pre-loader').removeClass('d-none');
                $.post('{{ route("update_payment_activation_status") }}', {_token:'{{ csrf_token() }}', id:this.value, status:status}, function(data){
                    if(data.status == 1){
                        toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                        $('#form_list_div').html(data.list);
                        $('#pre-loader').addClass('d-none');
                    }
                    else{
                        toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        $('#pre-loader').addClass('d-none');
                    }
                }).fail(function(response) {
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }

                });
            });

            $(document).on('change', '#paypal_logo', function(){

                getFileName($(this).val(),'#thumbnail_image_file');
                imageChangeWithFile($(this)[0],'#ThumbnailImgDiv');
            });

            $(document).on('change', '#logoStripe', function(){
                getFileName($(this).val(),'#logoStripe_file');
                imageChangeWithFile($(this)[0],'#logoStripeDiv');
            });

            $(document).on('change', '#logoPaystack', function(){
                getFileName($(this).val(),'#logoPaystack_file');
                imageChangeWithFile($(this)[0],'#logoPaystackDiv');
            });

            $(document).on('change', '#logoRazor', function(){
                getFileName($(this).val(),'#Razor_file');
                imageChangeWithFile($(this)[0],'#logoRazorDiv');
            });

            $(document).on('change', '#logoPaytm', function(){
                getFileName($(this).val(),'#Paytm_file');
                imageChangeWithFile($(this)[0],'#logoPaytmDiv');
            });

            $(document).on('change', '#logoInstamojo', function(){
                getFileName($(this).val(),'#Instamojo_file');
                imageChangeWithFile($(this)[0],'#logoInstamojoDiv');
            });

            $(document).on('change', '#logoMidtrans', function(){
                getFileName($(this).val(),'#logoMidtrans_file')
                imageChangeWithFile($(this)[0],'#logoMidtransDiv');
            });

            $(document).on('change', '#logoPayUmoney', function(){
                getFileName($(this).val(),'#logoPayUmoney_file');
                imageChangeWithFile($(this)[0],'#logoPayUmoneyDiv');
            });

            $(document).on('change', '#logoJazzCash', function(){
                getFileName($(this).val(),'#JazzCash_file');
                imageChangeWithFile($(this)[0],'#logoJazzCashDiv');
            });

            $(document).on('change', '#logogooglePay', function(){
                getFileName($(this).val(),'#googlePay_file');
                imageChangeWithFile($(this)[0],'#logogooglePayDiv');
            });

            $(document).on('change', '#logoFlutterWave', function(){
                getFileName($(this).val(),'#logoFlutterWave_file');
                imageChangeWithFile($(this)[0],'#logoFlutterWaveDiv');
            });

            $(document).on('change', '#logoPaddle', function(){
                getFileName($(this).val(),'#logoPaddle_file');
                imageChangeWithFile($(this)[0],'#logoPaddleDiv');
            });

            $(document).on('change', '#logobank', function(){
                getFileName($(this).val(),'#bank_image_file');
                imageChangeWithFile($(this)[0],'#BankImgDiv');
            });

            $(document).on('change', '#bkashlogo', function(){
                getFileName($(this).val(),'#bkash_image_file');
                imageChangeWithFile($(this)[0],'#BkashImgDiv');
            });

            $(document).on('change', '#ssl_commerz_logo', function(){
                getFileName($(this).val(),'#ssl_commerz_image_file');
                imageChangeWithFile($(this)[0],'#SSLImgDiv');
            });

            $(document).on('change', '#mercado_logo', function(){
                getFileName($(this).val(),'#mercado_image_file');
                imageChangeWithFile($(this)[0],'#MercadoImgDiv');
            });

        });

    })(jQuery);
    </script>
@endpush
