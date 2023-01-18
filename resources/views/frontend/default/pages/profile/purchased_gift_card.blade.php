@extends('frontend.default.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/purchased_gift_card.css'))}}" />
  
@endsection
@section('breadcrumb')
    {{ __('common.gift_card') }}
@endsection
@section('title')
    {{ __('common.gift_card') }}
@endsection

@section('content')

@include('frontend.default.partials._breadcrumb')

<!--  dashboard part css here -->
<section class="dashboard_part bg-white padding_top">
    <div class="container">
        <div class="row">
            @include('frontend.default.pages.profile.partials._menu')
            <div class="col-xl-9 col-md-7">
               <div class="coupons_item">
                   <div class="single_coupons_item cart_part">
                       <div class="table-responsive">
                        <table class="table table-hover red-header">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('common.amount') }}</th>
                                            <th scope="col">{{ __('common.name') }}</th>
                                            <th scope="col">{{ __('common.qty') }}</th>
                                            <th scope="col">{{ __('customer_panel.secret_code') }}</th>
                                            <th scope="col">{{ __('customer_panel.is_used') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="cart_table_body ">
                                    @foreach ($gift_card_infos as $key => $gift_card_info)
                                        <tr>
                                            <td><h4>{{ single_price($gift_card_info->giftCard->selling_price) }}</h4></td>
                                            <td>{{ $gift_card_info->giftCard->name }}</td>
                                            <td>{{ $gift_card_info->qty }}</td>
                                            <td class="text-center show_icon" data-secret-code="{{ $gift_card_info->secret_code }}"><i class="ti-eye"></i></td>
                                            <td>
                                                @if ($gift_card_info->is_used == 1)
                                                    <h6>{{ __('common.used') }}</h6>
                                                @else
                                                    <a class="btn_1 m-0 gift_card_redeem" data-gift-card-use-id='{{ $gift_card_info->id }}'>{{ __('common.redeem') }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                   </div>
                    @if (count($gift_card_infos) > 0)
                        <x-pagination-component :items="$gift_card_infos" type=""/>
                    @else
                        <div class="row mt-20">
                            <div class="col-lg-12 text-center">
                                <p class="mt-200">{{__('common.nothing_found')}}</p>
                            </div>
                        </div>
                    @endif
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
                $(document).on('click', '.gift_card_redeem', function(){
                    $(this).text('Please Wait.....');
                    var _this = this;
                    var gift_card_use_id = $(this).attr("data-gift-card-use-id");
                    $.post('{{ route('frontend.gift_card_redeem') }}', {_token:'{{ csrf_token() }}', gift_card_use_id:gift_card_use_id}, function(data){
                        if (data == 1) {
                            toastr.success("{{__('common.Money has been transfered into wallet')}}","{{__('common.success')}}")
                            $(_this).text('Done')
                        }else {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");

                            $(_this).text('Redeem Again')
                        }
                    });
                });
                $(document).on('click','.show_icon', function(){
                    $(this).text($(this).attr("data-secret-code"))
                });
            });
        })(jQuery);
    </script>
@endpush
