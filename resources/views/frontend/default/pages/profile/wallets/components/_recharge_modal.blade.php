<div class="modal fade admin-query" id="Item_Details">
    <div class="modal-dialog modal_800px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('wallet.enter_your_amount_to_recharge') }}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>

            <div class="modal-body">
                <section class="send_query bg-white contact_form">
                    <form action="{{route('my-wallet.recharge_create')}}" method="post" class="send_query_form" id="recharge_form">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('common.amount') }}<span class="text-danger">*</span></label>
                            <input type="number" min="0" step="{{step_decimal()}}" value="0" id="recharge_amount" name="recharge_amount" placeholder="{{ __('common.amount') }}" class="form-control">
                            <span class="text-danger"  id="error_amount"></span>
                        </div>
                        <div class="send_query_btn">
                            <button id="contactBtn" type="submit" class="btn_1">{{ __('common.continue') }}</button>
                        </div>
                    </form>
                </section>
            </div>

        </div>
    </div>
</div>