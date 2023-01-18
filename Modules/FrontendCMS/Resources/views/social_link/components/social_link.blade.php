<div class="main-title d-md-flex mb-25">
    <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('general_settings.social_link') }}</h3>

    <ul class="d-flex">
        <li>
            <a href="" id="add_new_shipping" class="primary-btn radius_30px mr-10 fix-gr-bg"><i class="ti-plus"></i>{{ __('common.add_new') }}</a>
            </li>

        </a>
        </li>
    </ul>
    @include('backEnd.partials._deleteModalForAjax',['item_name' => __('general_settings.social_link')])

</div>

<div class="common_QA_section QA_section_heading_custom">
    <div class="QA_table ">
        <!-- table-responsive -->
        <div id="socialListDiv">
            <table class="table Crm_table_active2">
                <thead>
                <tr>
                    <th scope="col">{{__('common.sl')}}</th>
                    <th scope="col">{{ __('common.url') }}</th>
                    <th scope="col">{{ __('common.icon') }}</th>
                    <th scope="col" class="text-center">{{ __('common.status') }}</th>
                    <th scope="col" class="text-center">{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($socialLinks as $key => $link)
                    <tr>
                        <td>{{$key +1}}</td>
                        <td><a href="">{{$link->url}}</a></td>
                        <td><i class="{{$link->icon}}"></i></td>
                        <td><span class="{{$link->status == 1?'badge_1':'badge_2'}}">{{ showStatus($link->status) }}</span></td>
                        
            
                        <td>
                            <!-- shortby  -->
                            <div class="dropdown CRM_dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('common.select') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
            
                                    <a href="#" data-value="{{$link}}" class="dropdown-item edit_link">{{ __('common.edit') }}</a>
            
                                    <a href="#" class="dropdown-item delete_link"
                                     data-id="{{$link->id}}">{{ __('common.delete') }}</a>
            
                                </div>
                            </div>
                            <!-- shortby  -->
                        </td>
            
                    </tr>
                    @endforeach
            
                </tbody>
            </table>
            
        </div>
    </div>
</div>

<div id="add_social_modal">
    <div class="modal fade" id="social_add">
        <div class="modal-dialog modal_800px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ __('common.add_new') }} {{ __('general_settings.social_link') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="ti-close "></i>
                    </button>
                </div>

                <div class="modal-body item_create_form">
                    
                    <div class="alert alert-warning">
                        {{ __('general_settings.fontawesome_or_themefy_icon_only') }} (fab fa-facebook or ti-facebook)
                    </div>

                    <form enctype="multipart/form-data" id="socialLinkCreate" action="" method="POST"
                       >

                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="icon">{{ __('common.icon') }}
                                        <span class="text-danger">*</span></label>
                                    <input name="icon" class="primary_input_field icon" id="icon"
                                        placeholder="{{ __('fab fa-facebook') }}" type="text" required>
                                    <span class="text-danger" id="error_icon"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="url">{{ __('general_settings.url') }} <span
                                            class="text-danger">*</span></label>
                                    <input name="url" class="primary_input_field name" id="url"
                                        placeholder="{{ __('general_settings.url') }}" type="text" required>
                                    <span class="text-danger" id="error_url"></span>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">{{ __('common.status') }}</label>
                                    <ul id="theme_nav" class="permission_list sms_list ">
                                        <li>
                                            <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                <input name="status" id="status_active" value="1" checked="true"
                                                    class="active" type="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ __('common.active') }}</p>
                                        </li>
                                        <li>
                                            <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                <input name="status" value="0" id="status_inactive" class="de_active"
                                                    type="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ __('common.inactive') }}</p>
                                        </li>
                                    </ul>
                                    <span class="text-danger" id="status_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="submit" id="social_add_btn"
                                        class="primary-btn semi_large2 fix-gr-bg"><i class="ti-check"></i>
                                        {{ __('common.save') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit_social_modal">
    <div class="modal fade" id="social_edit">
        <div class="modal-dialog modal_800px modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ __('common.edit') }} {{ __('general_settings.social_link') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="ti-close "></i>
                    </button>
                </div>

                <div class="modal-body item_create_form">
                    {{-- form --}}
                    <div class="alert alert-warning">
                        {{ __('general_settings.fontawesome_or_themefy_icon_only') }} (fab fa-facebook or ti-facebook)
                    </div>

                    <form enctype="multipart/form-data" id="socialLinkEdit" action="" method="POST"
                        enctype="multipart/form-data">

                        <div class="row">
                            <input type="hidden" name="id" id="id" value="">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="icon">{{__('common.icon')}}
                                        <span class="text-danger">*</span></label>
                                    <input name="icon" class="primary_input_field icon" id="iconEdit"
                                        placeholder="{{ __('fab fa-facebook') }}" type="text" required>
                                    <span class="text-danger" id="error_icon"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="url">{{ __('common.url') }} <span
                                            class="text-danger">*</span></label>
                                    <input name="url" class="primary_input_field name" id="urlEdit"
                                        placeholder="{{ __('common.url') }}" type="text" required>
                                    <span class="text-danger" id="error_url"></span>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">{{ __('common.status') }}</label>
                                    <ul id="theme_nav" class="permission_list sms_list ">
                                        <li>
                                            <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                <input name="status" id="status_activeEdit" value="1" checked="true"
                                                    class="active" type="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ __('common.active') }}</p>
                                        </li>
                                        <li>
                                            <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                <input name="status" value="0" id="status_inactiveEdit"
                                                    class="de_active" type="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                            <p>{{ __('common.inactive') }}</p>
                                        </li>
                                    </ul>
                                    <span class="text-danger" id="status_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center">
                                <div class="d-flex justify-content-center pt_20">
                                    <button type="submit" id="social_edit_btn"
                                        class="primary-btn semi_large2 fix-gr-bg"><i class="ti-check"></i>
                                        {{ __('common.save') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
