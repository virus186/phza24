@extends('backEnd.master')

@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('appearance.Custom asset') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{route("appearance.custom-asset-store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="white_box_50px box_shadow_white mb-20">
                            <div class="row">
                                
                                
                                <div class="col-xl-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{__('appearance.Custom CSS')}} ({{__('appearance.Without style tag')}})</label>
                                        <textarea class="primary_textarea" placeholder="{{__('appearance.Custom CSS')}} ({{__('appearance.Without style tag')}})" id="custom_css" cols="30" rows="10"
                                            name="custom_css">{{@$custom_css}}</textarea>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{__('appearance.Custom JS')}} ({{__('appearance.Without script tag')}})</label>
                                        <textarea class="primary_textarea" placeholder="{{__('appearance.Custom JS')}} ({{__('appearance.Without script tag')}})" id="custom_js" cols="30" rows="10"
                                            name="custom_js">{{@$custom_js}}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="primary_btn_2"><i class="ti-check"></i>{{__("common.save")}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection