@extends('frontend.amazy.layouts.app')
@section('content')
<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="dashboard_white_box style2 bg-white mb_25">
                    <div class="dashboard_white_box_header d-flex align-items-center">
                        <h4 class="font_24 f_w_700 mb_20">{{__('amazy.Add Coupons')}}</h4>
                    </div>
                    <form type="POST" id="couponForm">
                        <div class="d-flex gap_10 flex-sm-wrap flex-md-nowrap gray_color_1 theme_border padding25 mb_40">
                            <input name="code" id="code" placeholder="{{__('common.code')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.code')}}'" class="primary_input3 rounded-0 style2  flex-fill" type="text">
                            <button class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.add_coupon')}}</button>
                        </div>
                    </form>

                    <div class="dashboard_white_box_header d-flex align-items-center">
                        <h4 class="font_20 f_w_700 mb_20">{{__('amazy.Collected Coupons')}}</h4>
                    </div>
                    <div class="dashboard_white_box_body" id="couponDiv">
                        @include('frontend.amazy.pages.profile.partials._coupon_list') 
                    </div> 
                </div>
            </div>
        </div>
    </div>
    @include('frontend.amazy.partials._delete_modal_for_ajax',['item_name' => __('defaultTheme.coupon'),'form_id' => 'coupon_delete_form','modal_id' => 'coupon_delete_modal'])
</div>
@endsection
@push('scripts')
    <script>

        (function($){
            "use strict";

            $(document).ready(function(){
                $(document).on('submit','#couponForm', function(event){
                    event.preventDefault();
                    let code = $('#code').val();
                    if(code){
                        $('#pre-loader').show();
                        let formElement = $(this).serializeArray()
                        let formData = new FormData();
                        formElement.forEach(element => {
                            formData.append(element.name, element.value);
                        });
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('form', 'modal_form');
                        $.ajax({
                            url: "{{route('frontend.profile.coupon.store')}}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function (response) {
                                if(response.error){
                                    toastr.error(response.error,'Error');
                                    $('#pre-loader').hide();
                                }else{
                                    $('#couponDiv').html(response.CouponList);
                                    $('#pre-loader').hide();
                                    toastr.success("{{__('defaultTheme.coupon_store_successfully')}}","{{__('common.success')}}");
                                    $('#code').val('');
                                }
                            },
                            error: function (response) {
                                if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').hide();
                                    return false;
                                }
                                $('#pre-loader').hide();

                            }
                        });
                    }else{
                        toastr.error("{{__('defaultTheme.coupon_code_in_required')}}",'common.error');
                    }
                });

                $(document).on('click', '.page_link', function(event) {
                    event.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    fetch_filter_data(page);

                });

                function fetch_filter_data(page){
                    $('#pre-loader').show();
                    var url = "{{route('customer_panel.coupon.get-paginate')}}"+'?page='+page;
                    if(page != 'undefined'){
                        $.ajax({
                            url:url,
                            success:function(data)
                            {
                                $('#couponDiv').html(data);
                                $('#pre-loader').hide();
                            }
                        });
                    }else{
                        toastr.warning("{{__('common.error_message')}}");
                    }

                }

                $(document).on('click', '.coupon_delete_btn', function(event){
                    event.preventDefault();
                    let id = $(this).data('id');
                    $('#delete_item_id').val(id);
                    $('#coupon_delete_modal').modal('show');
                });

                $(document).on('submit', '#coupon_delete_form', function(event){
                    event.preventDefault();

                    couponDelete($('#delete_item_id').val());

                });
                function couponDelete(id){
                    $('#pre-loader').show();
                    $('#coupon_delete_modal').modal('hide');
                    let formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', id);
                    $.ajax({
                        url: "{{route('frontend.profile.coupon.delete')}}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function (response) {
                            $('#couponDiv').empty();
                            $('#couponDiv').html(response.CouponList);
                            $('#pre-loader').hide();
                            toastr.success("{{__('common.deleted_successfully')}}","{{__('common.success')}}");
                        },
                        error: function (response) {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                        }
                    });
                }
                
                $(document).on('click','.copyBtn', function(event){
                    let copyTextarea = document.createElement("textarea");
                    copyTextarea.style.position = "fixed";
                    copyTextarea.style.opacity = "0";
                    copyTextarea.textContent = $(this).data('code');
                    document.body.appendChild(copyTextarea);
                    copyTextarea.select();
                    document.execCommand("copy");
                    document.body.removeChild(copyTextarea);
                    toastr.success("{{__('defaultTheme.code_copied_successfully')}}", "{{__('common.success')}}");
                });
            });
        })(jQuery);

    </script>
@endpush