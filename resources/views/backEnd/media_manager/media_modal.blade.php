<div class="modal fade admin-query" id="media_modal">
    <div class="modal-dialog modal_1688px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="box_header_right">
                    <div class="float-lg-right float-none pos_tab_btn justify-content-end">
                        <ul class="nav nav_list" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" href="#order_processing_data" role="tab"
                                    data-toggle="tab" id="product_list_id" aria-selected="true">{{__('Select Files')}}</a>
                            </li>
                            @if(permissionCheck('media-manager.new-upload'))
                            <li class="nav-item">
                                <a class="nav-link" href="#order_complete_data" role="tab" data-toggle="tab" id="product_request_id"
                                    aria-selected="true">{{__('Uplaod New')}}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="white_box_0px m-0">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active show" id="order_processing_data">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="header-part-media-modal">
                                        <div class="row align-content-center">
                                            <div class="col-lg-12 d-flex align-items-center gap_20 flex-wrap">
                                                <div class="primary_input max_340px">
                                                    <select class="primary_select max_340px" name="Amaz_media_sort" id="status">
                                                        <option value="newest">{{ __('Sort by newest') }}</option>
                                                        <option value="oldest">{{ __('Sort by oldest') }}</option>
                                                        <option value="smallest">{{ __('Sort by smallest') }}</option>
                                                        <option value="bigest">{{ __('Sort by bigest') }}</option>
                                                    </select>
                                                </div>
                                                <div class="primary_input ">
                                                    <ul id="theme_nav" class="permission_list sms_list ">
                                                        <li class="m-0">
                                                            <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                                <input name="selected_only" id="selected_only" value="1"
                                                                    type="checkbox">
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <p>{{ __('Selected Only') }}</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="primary_input flex-fill d-flex justify-content-end">
                                                    <input class="primary_input_field max_340px" name="amaz_media_search" placeholder="Search" type="text" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="file-list-div mt-50">
                                        <div class="amazcart_file_wrapper style2" id="all_files_div">
                                            <div class="loader_media">
                                                <div class="hhhdots_1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(permissionCheck('media-manager.new-upload'))
                        <div role="tabpanel" class="tab-pane fade" id="order_complete_data">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="AmazUppyDragDrop"></div>
                                    <div class="for-ProgressBar"></div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer p-0 border-0">
                <div class="controll_wrapper d-flex flex-wrap gap_20 w-100 media_list_controller">
                    <div class="select_and_reset_div d-flex align-items-center gap_20 flex-fill p-0">
                        <h4 class="upload_files_selected"> 0 File Selected</h4>
                        <a href="" class="primary_btn_2 reset_selected text-bold"> Reset</a>
                    </div>
                    <div class="next_prev_btn_div  d-flex align-items-center gap_10 p-0 flex-wrap">
                        <button type="button" class="primary_btn_2" id="uploader_prev_btn">Prev</button>
                        <button type="button" class="primary_btn_2" id="uploader_next_btn">Next</button>
                        <button type="button" class="primary_btn_2" data-toggle="amazUploaderAddSelected">Add files</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
