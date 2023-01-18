<!-- wallet_modal::start  -->
<div class="modal fade theme_modal2" id="recharge_wallet" tabindex="-1" role="dialog" aria-labelledby="theme_modal" aria-hidden="true">
    <div class="modal-dialog max_width_700 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <form action="{{route('my-wallet.recharge_create')}}" method="post" class="send_query_form" id="recharge_form">
                    @csrf
                    <div class="payment_modal_wallet">
                        <h3 class="font_24 f_w_700 mb_18">{{__('amazy.Recharge Amount')}}</h3>
                        <input type="number" min="0" step="{{step_decimal()}}" value="" id="recharge_amount" name="recharge_amount" placeholder="Enter Recharge Amount" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Recharge Amount'" class="primary_input3 rounded-0 style2  mb_15">
                        <span class="text-danger"  id="error_amount"></span>
                        <div class="d-flex justify-content-end gap_30 align-items-center">
                            <h5 class="font_14 f_w_700 text-uppercase gj-cursor-pointer m-0" data-bs-dismiss="modal">{{__('common.cancel')}}</h5>
                            <button class="amaz_primary_btn style2 text-nowrap">{{__('defaultTheme.add_fund')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- wallet_modal::end  -->