<div id="show_item_modal">
    <div class="modal fade" id="item_show">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ __('File Detaill Info') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="ti-close "></i>
                    </button>
                </div>

                <div class="modal-body item_edit_form">
                    <h5>{{__('common.name')}}: <p class="d-inline" id="show_name"></p></h5>
                    <h5>{{__('common.slug')}}: <p class="d-inline" id="show_path"></p></h5>
                    <h5>{{__('Extension')}}: <p class="d-inline" id="show_extension"></p></h5>
                    <h5>{{__('Size')}}: <p class="d-inline" id="show_size"></p></h5>
                    <h5>{{__('Storage Type')}}: <p class="d-inline" id="show_storage"></p></h5>

                    <div class="row" id="single_image_div">
                        <div class="col-12">
                            <div class="show_img_div">
                                <img id="view_image">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
