@extends('backEnd.master')

@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('modules/appearance/css/theme.css'))}}" />

@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="box_header">
                        <div class="main-title d-flex justify-content-between">
                            <h3 class="mb-0 mr-30">{{ __('appearance.themes') }}</h3>
                            @if (permissionCheck('appearance.themes.store'))
                                <ul class="d-flex">
                                <li><a class="primary-btn radius_30px mr-10 fix-gr-bg text-white" href="{{route('appearance.themes.create')}}" dusk="Add New"><i class="ti-plus"></i>{{__('common.add_new')}}</a></li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 item_section">
                            <div class="card default_card_border theme_full_100">

                                <div class="card-body screenshot p-0 flex-fill">
                                    <div class="single_item_img_div">
                                        <img src="{{ showImage($activeTheme->image) }}" alt="">
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <h4>{{$activeTheme->name}}</h4>
                                        </div>
                                        @if($activeTheme->is_active !=1 )
                                        <div class="col-lg-7 footer_div">
                                            <div class="row btn_div">
                                                <div class="col-md-5 col-sm-12">
                                                    @if (permissionCheck('appearance.themes.active'))
                                                        <form action="{{route('appearance.themes.active')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$activeTheme->id}}">
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary Active_btn">{{__('common.active')}}</button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <div class="col-md-7 col-sm-12 p_l_0">
                                                <a class="btn btn-sm btn-outline-secondary Active_btn" target="_blank" href="{{$activeTheme->live_link}}">{{__('appearance.live_preview')}}</a>
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if (permissionCheck('appearance.themes.show'))
                                    <div class="text-center detail_btn">
                                    <h4><a href="{{route('appearance.themes.show',$activeTheme->id)}}">{{__('appearance.theme_details')}}</a></h4>
                                    </div>
                                @endif
                            </div>


                        </div>

                        @foreach($ThemeList as $key => $item)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 item_section">
                            <div class="card theme_full_100">

                                <div class="card-body screenshot p-0 flex-fill">
                                    <div class="single_item_img_div">
                                        <img src="{{ showImage($item->image) }}" alt="">
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <h4>{{$item->name}}</h4>
                                        </div>

                                        <div class="col-lg-7 col-md-7 footer_div d-flex justify-content-end  ">


                                            <div class="row">
                                                <div class="col-md-5 col-sm-12 text-center">
                                                    @if(!empty($item->purchase_code) || $item->name=='default' || empty($item->item_code))
                                                        <form action="{{route('appearance.themes.active')}}"
                                                              method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <button type="submit"
                                                                    class="primary-btn radius_30px mr-10   fix-gr-bg text-white pl-3 pr-3">
                                                                {{__('common.active')}}
                                                            </button>
                                                        </form>
                                                        @if(!empty($item->item_code))
                                                            @includeIf('service::license.revoke-theme', ['name' =>$item->name])
                                                        @endif
                                                    @else
                                                        <a class=" verifyBtn primary-btn radius_30px mr-10   fix-gr-bg text-white pl-3 pr-3"
                                                           data-toggle="modal" data-id="{{@$item->name}}"
                                                           data-target="#Verify"
                                                           href="#">   {{__('appearance.verify')}}</a>
                                                    @endif
    
                                                </div>
                                                <div style="padding-left: 0;" class="col-md-7 col-sm-12">
                                                    <a class="primary-btn radius_30px mr-10   fix-gr-bg text-white pl-3 pr-3" target="_blank" href="{{$item->live_link}}">{{__('appearance.live_preview')}}</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @if (permissionCheck('appearance.themes.show'))
                                    <div class="text-center detail_btn">
                                        <h4><a href="{{route('appearance.themes.show',$item->id)}}" dusk="view details">{{__('appearance.theme_details')}}</a></h4>
                                    </div>
                                @endif
                            </div>


                        </div>
                        @endforeach

                        @if (permissionCheck('appearance.themes.store'))
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                <a href="{{route('appearance.themes.create')}}" class="theme_full_100 d-flex align-items-center justify-content-center " id="add_new" >
                                    <span id="plus"><i class="fas fa-plus"></i></span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade admin-query" id="Verify">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Module Verification</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;
                        </button>
                    </div>
    
                    <div class="modal-body">
                        {{ Form::open(['id'=>"content_form",'class' => 'form-horizontal', 'files' => true, 'route' => 'service.theme.install', 'method' => 'POST']) }}
                        <input type="hidden" name="name" value="" id="moduleName">
                        @csrf
                        <div class="form-group">
                            <label for="user">Envato Email Address :</label>
                            <input type="text" class="form-control " name="envatouser"
                                   required="required"
                                   placeholder="Enter Your Envato Email Address"
                                   value="{{old('envatouser')}}">
                        </div>
                        <div class="form-group">
                            <label for="purchasecode">Envato Purchase Code:</label>
                            <input type="text" class="form-control" name="purchase_code"
                                   required="required"
                                   placeholder="Enter Your Envato Purchase Code"
                                   value="{{old('purchasecode')}}">
                        </div>
                        <div class="form-group">
                            <label for="domain">Installation Path:</label>
                            <input type="text" class="form-control"
                                   name="installationdomain" required="required"
                                   placeholder="Enter Your Installation Domain"
                                   value="{{url('/')}}" readonly>
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg submit">
                                    <span class="ti-check"></span>
                                    {{__('setting.Verify')}}
                                </button>
                                <button type="button" class="primary-btn fix-gr-bg submitting" style="display: none">
                                    <i class="fas fa-spinner fa-pulse"></i>
                                    Verifying
                                </button>
                            </div>
                        </div>
    
                        {{ Form::close() }}
                    </div>
    
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{asset('public/backend/js/module.js')}}"></script>
    <script type="text/javascript" src="{{ asset('public/vendor/spondonit/js/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/vendor/spondonit/js/function.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/vendor/spondonit/js/common.js') }}"></script>
    <script type="text/javascript">
        _formValidation('content_form');
    </script>
@endpush
