@extends('backEnd.master')

@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">

            <div class="row">
                <div class="col-lg-12">
                    <div class="main-title mb-25">
                        <h3 class="mb-0">Payment Gateway Global Setting</h3>
                    </div>
                </div>
                <div class="col-md-5 col-sm-6 col-xs-12">
        
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
                                                    {{ strtoupper(str_replace("_"," ",$gateway_activation->method)) }}
                                                    @if(config('app.sync'))
                                                        @if($gateway_activation->method == 'Bkash' || $gateway_activation->method == 'Mercado Pago' || $gateway_activation->method == 'SslCommerz')
                                                            <span class="demo_addons">Addon</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <label class="switch_toggle" for="checkbox{{ $gateway_activation->id }}">
                                                        <input type="checkbox" id="checkbox{{ $gateway_activation->id }}" @if ($gateway_activation->active_status == 1) checked @endif @if (permissionCheck('update_payment_activation_status')) value="{{ $gateway_activation->id }}" class="payment_gateways_activate" @else disabled @endif>
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
                
                <div class="col-lg-6">
                    <div class="white_box_50px box_shadow_white mb-20">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">{{
                                        __('Seller to seller payment gateway') }} <span
                                            class="text-danger">*</span></label>
                                    <ul id="theme_nav" class="permission_list sms_list ">
                                        <li>
                                            <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                <input name="seller_wise_payment" id="seller_wise_payment_active" value="1" class="active" {{app('general_setting')->seller_wise_payment?'checked':''}} type="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ __('common.on') }}</p>
                                        </li>
                                        <li>
                                            <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                <input name="seller_wise_payment" value="0" id="seller_wise_payment_inactive"
                                                    class="de_active" type="radio" {{app('general_setting')->seller_wise_payment?'':'checked'}}>
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ __('common.off') }}</p>
                                        </li>
                                    </ul>
                                    <span class="text-danger" id="seller_wise_payment_error"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="primary_btn_2" id="saveSetting"><i class="ti-check"></i>{{__("common.update")}} </button>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>

        </div>
    </section>
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
                    $.post('{{ route("setting_payment_activation_status") }}', {_token:'{{ csrf_token() }}', id:this.value, status:status}, function(data){
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

                $(document).on('click', '#saveSetting', function(event){
                    var status = $('input[name="seller_wise_payment"]:checked').val();
                    var data = {
                        _token : "{{csrf_token()}}",
                        status : status
                    }
                    $('#pre-loader').removeClass('d-none');
                    $.post('{{route("paymentgateway.setting_update")}}',data, function(response){
                        if(response.msg == 'success'){
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                        }else{
                            toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        }
                        $('#pre-loader').addClass('d-none');
                    }).fail(function(response) {
                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endpush