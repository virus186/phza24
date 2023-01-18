<!-- checkout_login_form:start -->
<div class="modal fade login_modal" id="checkot_login_form" tabindex="-1" role="dialog" aria-labelledby="checkot_login_form" aria-hidden="true">
    <div class="modal-dialog style2 modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div data-bs-dismiss="modal" class="close_modal">
                    <i class="ti-close"></i>
                </div>
                <!-- amaz_checkout_loginArea::start  -->
                <div class="amaz_checkout_loginArea p-0">
                    <div class="login_area_inner">
                        <h4 class="text-start">{{__('amazy.Welcome back')}}<br>
                            {{__('defaultTheme.please_login_to_your_account')}}</h4>
                        <form action="#">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group custom_group_field mb_35">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <img src="{{url('/')}}/public/frontend/amazy/img/my_account/email.svg" alt="E-Mail" title="E-Mail">
                                            </span>
                                        </div>
                                        <input type="email" class="form-control" placeholder="E.g. example@gmail.com" aria-label="E.g. example@gmail.com" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-group custom_group_field ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <img src="{{url('/')}}/public/frontend/amazy/img/my_account/pass.svg" alt="Password" title="Password">
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" placeholder="Enter Password" aria-label="Enter Password" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="remember_pass mb_40">
                                        <label class="primary_checkbox d-flex">
                                            <input checked="" type="checkbox">
                                            <span class="checkmark mr_15"></span>
                                            <span class="label_name">{{__('defaultTheme.remember_me')}}</span>
                                        </label>
                                        <a class="forgot_pass" href="#">{{__('defaultTheme.forgot_password')}}</a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center">{{__('amazy.Sign In')}}</button>
                                </div>
                                <div class="form_sep d-flex align-items-center">
                                    <span class="sep_line flex-fill"></span>
                                    <span class="form_sep_text font_14 f_w_700 text-uppercase ">OR</span>
                                    <span class="sep_line flex-fill"></span>
                                </div>
                                <div class="col-12">
                                    <button data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#checkot_login_form_reg" class="amaz_primary_btn2  style2 radius_5px text-center  w-100 text-uppercase text-center justify-content-center">{{__('amazy.register_now')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- amaz_checkout_loginArea::end  -->
            </div>
        </div>
    </div>
</div>
<!-- checkout_login_form:end  -->