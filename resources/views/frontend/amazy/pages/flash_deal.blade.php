@extends('frontend.amazy.layouts.app')
@section('title')
    {{ __('common.flash_deals') }}
@endsection
@section('content')

@php
    $start_date = date('Y/m/d',strtotime($Flash_Deal->start_date));
    $end_date = date('Y/m/d',strtotime($Flash_Deal->end_date));
    $current_date = date('Y/m/d');
    $deal_date = '1990/01/01';
    if($start_date<= $current_date && $end_date >= $current_date){
        $deal_date = $end_date;
    }
    elseif ($start_date >= $current_date && $end_date >= $current_date) {
        $deal_date = $start_date;
    }

@endphp
@if($Flash_Deal->banner_image)
    <div class="flash_deal_banner mb_30">
        <img src="{{ showImage($Flash_Deal->banner_image) }}" alt="{{@$Flash_Deal->title}}" title="{{@$Flash_Deal->title}}" class="img-fluid w-100">
    </div>
@endif
<div class="new_user_section section_spacing6 pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__title d-flex align-items-center gap-3 m-0 flex-wrap">
                    <h3 class="m-0 flex-fill">
                        @if($start_date <= $current_date && $end_date >= $current_date)
                        {{__('defaultTheme.deal_ends_in')}}
                        @elseif($start_date >= $current_date && $end_date >= $current_date)
                        {{__('defaultTheme.deal_starts_in')}}
                        @else
                        {{__('defaultTheme.deal_ends')}}
                        @endif
                    </h3>
                    <div id="count_down" class="deals_end_count amazy_date_counter"></div>
                </div>
                <div class="amazy_bb mb_30 mt_20"></div>
            </div>
        </div>

        <div id="productShow">
            @include('frontend.amazy.partials.flash_deal_paginate_data')
        </div>



    </div>
</div>
@endsection
@include(theme('partials.add_to_cart_script'))
@include(theme('partials.add_to_compare_script'))
@push('scripts')

<script>
    (function($){
        "use strict";

        $(document).ready(function(){
            $(document).on('click', '.page_link', function(event){
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page){
                $('#pre-loader').show();
                if(page != 'undefined'){
                    $.ajax({
                    url:"{{route('frontend.flash-deal.fetch-data',$Flash_Deal->slug)}}"+'?page='+page,
                    success:function(data)
                    {
                        $('#productShow').html(data);
                        $('#product_short_list').niceSelect();
                        $('#pre-loader').hide();
                    }
                    });
                }else{
                    toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                }
            }

            if ($("#count_down").length > 0) {
                $("#count_down").countdown('{{$deal_date}}', function (event) {
                    $(this).html(
                        event.strftime(
                        '<div class="single_count"><span>%D</span><p>Days</p></div><div class="single_count"><span>%H</span><p>Hours</p></div><div class="single_count"><span>%M</span><p>Minutes</p></div><div class="single_count"><span>%S</span><p>Seconds</p></div>'
                        )
                    );
                });
            }
        });
    })(jQuery);

</script>
@endpush
