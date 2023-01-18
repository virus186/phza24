@extends('frontend.amazy.layouts.app')
@section('title')
    {{ __('customer_panel.purchased_digital_products') }}
@endsection
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
                </div>
                <div class="dashboard_white_box bg-white mb_25 pt-0 ">
                    <div class="dashboard_white_box_body">
                        <div class="table-responsive mb_30">
                            @if($digital_products->count())
                            <table class="table amazy_table2 mb-0">
                                <thead>
                                    <tr>
                                    <th class="font_14 f_w_700" scope="col">{{ __('common.name') }}</th>
                                    <th class="font_14 f_w_700 border-start-0 border-end-0" scope="col">{{ __('common.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($digital_products as $key => $digital_product)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <h4 class="font_16 f_w_700  ">{{ @$digital_product->seller_product_sku->product->product_name }}</h4>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="amazy_status_btns d-flex gap_5 align-items-center">
                                                    <a href="{{ route('digital_file_download', encrypt($digital_product->id)) }}" class="amazy_status_btn">
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
                            @else
                                <p class="mt-200 text-center mt_60">{{__('common.nothing_found')}}</p>
                            @endif
                        </div>
                        @if ($digital_products->lastPage() > 1)
                            <x-pagination-component :items="$digital_products" type=""/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
