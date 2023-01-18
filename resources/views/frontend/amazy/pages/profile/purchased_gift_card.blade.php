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
                    <div class="dashboard_white_box_body">
                        <div class="table-responsive">
                            <table class="table amazy_table style2 mb-0">
                                <thead>
                                    <tr>
                                    <th class="font_14 f_w_700 text-no" scope="col">{{__('common.amount')}}</th>
                                    <th class="font_14 f_w_700 border-start-0 border-end-0 text-no" scope="col">{{__('common.name')}}</th>
                                    <th class="font_14 f_w_700 border-start-0 border-end-0 text-no" scope="col">{{__('common.qty')}}</th>
                                    <th class="font_14 f_w_700 border-start-0 border-end-0 text-nowrap" scope="col">{{__('common.secret_code')}}</th>
                                    <th class="font_14 f_w_700 text-no" scope="col">{{__('customer_panel.is_used')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gift_card_infos as $key => $gift_card_info)
                                    <tr>
                                        <td>
                                            <span class="font_14 f_w_500 text-nowrap">{{ single_price($gift_card_info->giftCard->selling_price) }}</span>
                                        </td>
                                        <td>
                                            <span class="font_14 f_w_500">{{ textLimit($gift_card_info->giftCard->name,22) }}</span>
                                        </td>
                                        <td>
                                            <span class="font_14 f_w_500">{{ $gift_card_info->qty }}</span>
                                        </td>
                                        <td>
                                            <span class="font_14 f_w_500 text-nowrap">{{ $gift_card_info->secret_code }}</span>
                                        </td>
                                        <td>
                                            @if ($gift_card_info->is_used == 1)
                                            <a href="javascript:void(0);" class="line_badge_btn2 text-nowrap text-uppercase text-center">{{ __('common.used') }}</a>
                                            @else
                                            <a href="" class="line_badge_btn text-nowrap text-uppercase text-center gift_card_redeem" data-gift-card-use-id="{{ $gift_card_info->id }}">{{ __('common.redeem') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($gift_card_infos->lastPage() > 1)
                                <x-pagination-component :items="$gift_card_infos" type=""/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function(){
                $(document).on('click', '.gift_card_redeem', function(e){
                    e.preventDefault();
                    $(this).text('Please Wait.....');
                    var _this = this;
                    var gift_card_use_id = $(this).attr("data-gift-card-use-id");
                    $.post('{{ route('frontend.gift_card_redeem') }}', {_token:'{{ csrf_token() }}', gift_card_use_id:gift_card_use_id}, function(data){
                        if (data == 1) {
                            toastr.success("{{__('common.Money has been transfered into wallet')}}","{{__('common.success')}}")
                            $(_this).text('Done')
                            location.reload();
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