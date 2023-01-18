<!-- wallet_modal::start  -->
<div class="modal fade theme_modal2" id="cart_add_modal" tabindex="-1" role="dialog" aria-labelledby="theme_modal" aria-hidden="true">
    <div class="modal-dialog max_width_430 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="add_cart_modalAdded">
                    <button type="button" class="close_modal_icon" data-bs-dismiss="modal">
                        <i class="ti-close"></i>
                    </button>
                    <div class="product_checked_box d-flex flex-column justify-content-center align-items-center">
                        <svg id="checked" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                            <g id="Group_1587" data-name="Group 1587" transform="translate(7.118 3.77)">
                                <g id="Group_1586" data-name="Group 1586">
                                <path id="Path_3246" data-name="Path 3246" d="M143.592,64.66a1.131,1.131,0,0,0-1.6,0L128.426,78.189l-4.895-5.316a1.131,1.131,0,0,0-1.664,1.532l5.692,6.182a1.13,1.13,0,0,0,.808.365h.024a1.132,1.132,0,0,0,.8-.33l14.4-14.363A1.131,1.131,0,0,0,143.592,64.66Z" transform="translate(-121.568 -64.327)" fill="#4cb473"/>
                                </g>
                            </g>
                            <g id="Group_1589" data-name="Group 1589">
                                <g id="Group_1588" data-name="Group 1588">
                                <path id="Path_3247" data-name="Path 3247" d="M28.869,13.869A1.131,1.131,0,0,0,27.739,15,12.739,12.739,0,1,1,15,2.261,1.131,1.131,0,1,0,15,0,15,15,0,1,0,30,15,1.131,1.131,0,0,0,28.869,13.869Z" fill="#4cb473"/>
                                </g>
                            </g>
                        </svg>
                        <h4>{{__('defaultTheme.item_added_to_your_cart')}}</h4>
                    </div>
                    <div class="cart_added_box">
                        <a id="cart_suceess_url" class="cart_added_box_item d-flex align-items-center gap_25 flex-sm-wrap flex-md-nowrap">
                            <div class="thumb">
                                <img class="img-fluid" id="cart_suceess_thumbnail" src="{{url('/')}}/public/frontend/amazy/img/cart_added_thumb.png" alt="" title="">
                            </div>
                            <div class="cart_added_content">
                                <h4 id="cart_suceess_name"></h4>
                                <h5 id="cart_suceess_price"></h5>
                            </div>
                        </a>
                    </div>
                    <div class="d-flex flex-column gap_10">
                        <a href="{{url('/cart')}}" class="amaz_primary_btn style2 text-uppercase ">{{__('common.view_cart')}}</a>
                        @if(!app('general_setting')->seller_wise_payment && !isModuleActive('MultiVendor'))
                            <a href="{{url('/checkout')}}" class="amaz_primary_btn style2 text-uppercase ">{{__('common.process_to_checkout')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- wallet_modal::end  -->