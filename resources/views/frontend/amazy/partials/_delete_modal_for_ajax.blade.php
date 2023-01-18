<!-- Modal -->
<div class="modal theme_modal2 fade" id="{{ isset($modal_id) ? $modal_id : 'deleteItemModal' }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog max_width_570 modal-dialog-centered" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('common.delete') {{ $item_name }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> --}}
            <div class="modal-body p-0">
                <div class="payment_modal_wallet style2">
                    <div class="d-flex align-items-center gap_10 mb_30">
                        <h3 class="font_24 f_w_700  flex-fill mb-0">@lang('common.delete') {{ $item_name }}</h3>
                        <button type="button" class="close_modal_icon" data-bs-dismiss="modal">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>
                <div class="text-center">
                    <h4>@lang('common.are_you_sure_to_delete_?')</h4>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row w-100">
                    <div class="col-lg-12 justify-content-between d-flex">
                        <button type="button" class="amaz_primary_btn3 text-center justify-content-center text-uppercase" data-bs-dismiss="modal"
                        aria-label="Close">@lang('common.cancel')</button>
                        <form id="{{ isset($form_id) ? $form_id : 'item_delete_form' }}" class="p-0">
                            <input type="hidden" name="id"
                                id="{{ isset($delete_item_id) ? $delete_item_id : 'delete_item_id' }}">
                            <button type="submit" class="amaz_primary_btn style2  add_to_cart text-uppercase flex-fill text-center"
                                id="{{ isset($dataDeleteBtn) ? $dataDeleteBtn : 'dataDeleteBtn' }}">{{ __('common.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
