@extends('frontend.default.layouts.app')
@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/digital_purchased_product.css'))}}" />
   
@endsection
@section('breadcrumb')
{{ __('customer_panel.purchased_digital_products') }}
@endsection
@section('title')
{{ __('customer_panel.purchased_digital_products') }}
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
                                        <th>{{ __('common.name') }}</th>
                                        <th width="10%">{{ __('common.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="cart_table_body">
                                    @foreach ($digital_products as $key => $digital_product)
                                        <tr>
                                            <td>{{ @$digital_product->seller_product_sku->product->product_name }}</td>
                                            <td>
                                                <a class="btn_1 gift_card_redeem" href="{{ route('digital_file_download', encrypt($digital_product->id)) }}">Download</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                   </div>
                   
                    @if ($digital_products->lastPage() > 1)
                        <x-pagination-component :items="$digital_products" type=""/>
                    @elseif(!$digital_products->count())
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
