@extends('frontend.amazy.layouts.app')
@push('styles')
    <style>
        .payment_modal_wallet.style2 {
            padding:20px 45px 10px 25px;
        }
        .modal-footer .amaz_primary_btn3{
            margin-left: -15px;
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
            <div class="col-xl-9 col-lg-8" id="productShow">
                @include('frontend.amazy.pages.profile.partials._follow_customer_list')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>

        (function($){
            "use strict";

            $(document).ready(function(){
                $(document).on("click",".unfollow_btn" ,function(event){  
                    event.preventDefault();
                    let id = $(this).data('seller');
                    let data = {
                        seller_id: id,
                        _token : "{{csrf_token()}}"
                    }
                    console.log(data);
                    $('#pre-loader').show();
                    $(this).prop("disabled",true);
                    $.post("{{route('frontend.unfollow_seller')}}",data,function(response){
                        if(response.message == 'success'){
                            toastr.success("{{__('amazy.Unfollowed Successfully')}}","{{__('common.success')}}");
                            location.reload();
                        }
                        else{
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').hide();
                        }
                    });  
                });

                $(document).on('click', '.page_link', function(event){
                    event.preventDefault();
                    let current_page = $(this).attr('href');
                    let url = current_page;
                    $('#pre-loader').show();
                    location.replace(url)
                });

            });
        })(jQuery);

    </script>
@endpush