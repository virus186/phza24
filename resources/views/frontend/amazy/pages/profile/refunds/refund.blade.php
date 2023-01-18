@extends('frontend.amazy.layouts.app')
@section('content')
<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-9 col-lg-8">
                @foreach ($my_refund_items as $key => $my_refund_item)
                    <div class="white_box style2 bg-white mb_20">
                        <div class="white_box_header d-flex align-items-center gap_20 flex-wrap  amazy_bb3 justify-content-between ">
                            <div class="d-flex flex-column  ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.order_id')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $my_refund_item->order->order_number }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_date')}} : </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $my_refund_item->order->created_at }}</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('common.status')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $my_refund_item->CheckConfirmed }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.request_sent_date')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ $my_refund_item->created_at }}</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column  ">
                                <div class="d-flex align-items-center flex-wrap gap_5">
                                    <h4 class="font_14 f_w_500 m-0 lh-base">{{__('defaultTheme.order_amount')}}: </h4> <p class="font_14 f_w_400 m-0 lh-base"> {{ single_price( $my_refund_item->total_return_amount) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard_white_box_body">
                            <div class="table-responsive mb_10">
                                <table class="table amazy_table3 style2 mb-0">
                                    <tbody>
                                        @foreach ($my_refund_item->refund_details as $key => $refund_detail)
                                            @foreach ($refund_detail->refund_products as $key => $refund_product)
                                                <tr>
                                                    <td>
                                                        <a href="product_details.php" class="d-flex align-items-center gap_20 cart_thumb_div">
                                                            <div class="thumb">
                                                                <img src="
                                                                    @if (@$refund_product->seller_product_sku->sku->product->product_type == 1)
                                                                        {{showImage(@$refund_product->seller_product_sku->sku->product->thumbnail_image_source)}}
                                                                    @else
                                                                        @if (@$refund_product->seller_product_sku->sku->variant_image)
                                                                            {{showImage(@$refund_product->seller_product_sku->sku->variant_image)}}
                                                                        @else
                                                                            {{showImage(@$refund_product->seller_product_sku->sku->product->thumbnail_image_source)}}
                                                                        @endif
                                                                    @endif
                                                                " alt="">
                                                            </div>
                                                            <div class="summery_pro_content">
                                                                <h4 class="font_16 f_w_700 text-nowrap m-0 theme_hover">{{ textLimit(@$refund_product->seller_product_sku->sku->product->product_name, 30) }}</h4>
                                                                @if(@$refund_product->seller_product_sku->sku->product->product_type == 2)
                                                                    <p class="font_14 f_w_400 m-0 ">
                                                                        @php
                                                                            $countCombinatiion = count(@$refund_product->seller_product_sku->product_variations);
                                                                        @endphp
                                                                        @foreach(@$refund_product->seller_product_sku->product_variations as $key => $combination)
                                                                            @if($combination->attribute->name == 'Color')
                                                                                {{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}
                                                                            @else
                                                                                {{$combination->attribute->name}}: {{$combination->attribute_value->value}}
                                                                            @endif

                                                                            @if(!$loop->last), @endif
                                                                        @endforeach
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{__('common.qty')}}: {{ $refund_product->return_qty }}</h4>
                                                    </td>
                                                    <td>
                                                        <h4 class="font_16 f_w_500 m-0 text-nowrap">{{ single_price($refund_product->return_amount / $refund_product->return_qty) }}</h4>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('refund.frontend.my_refund_order_detail', encrypt($my_refund_item->id)) }}" class="amaz_primary_btn style2 text-nowrap ">{{__('defaultTheme.view_details')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($my_refund_items->lastPage() > 1)
                    <x-pagination-component :items="$my_refund_items" type=""/>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection