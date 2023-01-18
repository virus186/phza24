@extends('frontend.amazy.layouts.app')
@section('title')
{{ __('ticket.ticket') }}
@endsection
@section('content')
<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="dashboard_white_box style4 bg-white mb_25">
                    <div class="dashboard_white_box_header d-flex align-items-center gap_20 flex-wrap mb_35">
                        <h4 class="font_24 f_w_700 flex-fill m-0">{{__('ticket.create_new_ticket')}} </h4>
                    </div>
                    <div class="dashboard_white_box_body">
                         <!-- form  -->
                         <form action="{{route('frontend.support-ticket.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb_20">
                                    <label class="primary_label2 style2 ">{{__('common.subject')}} <span>*</span></label>
                                    <input name="subject" id="subject" placeholder="{{__('common.subject')}}" value="{{old('subject')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('common.subject')}}'" class="primary_input3 style4" type="text">
                                    @if ($errors->has('subject'))
                                        <span class="validation-name-info-error text-danger info_error">{{ $errors->first('subject') }}</span>
                                    @endif
                                </div>
                                <div class="col-xl-6 mb_20">
                                    <label class="primary_label2 style2 ">{{ __('common.category') }}<span>*</span></label>
                                    <select class="theme_select style2 wide" id="category_id" name="category_id">
                                        <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                        @foreach($categories as $key => $category)
                                        <option value="{{$category->id}}" {{(old('category_id') == $category->id)?'selected':''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <span class="text-danger" id="error_category_id">{{ $errors->first('category_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-xl-6 mb_20">
                                    <label class="primary_label2 style2 ">{{ __('ticket.priority') }}<span>*</span></label>
                                    <select class="theme_select style2 wide" id="priority_id" name="priority_id">
                                        <option value="">{{__('defaultTheme.select_from_options')}}</option>
                                        @foreach($priorities as $key => $priority)
                                        <option value="{{$priority->id}}" {{(old('priority_id') == $priority->id)?'selected':''}}>{{$priority->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('priority_id'))
                                        <span class="text-danger" id="error_priority_id">{{ $errors->first('priority_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-12 mb_20">
                                    <label class="primary_label2 style2 " for="description">{{__('common.description')}} <span>*</span></label>
                                    <textarea id="description" name="description" class="primary_textarea4 radius_5px mb_25">{{old('description')}}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger" id="error_message">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                                <div class="col-12 mb_20">
                                    <div class="tkt_uploader d-flex align-items-center gap_15 position-relative justify-content-center">
                                    <input type="file" name="ticket_file[]" id="ticket_file"
                                    data-value="#attach" class="attach_file_change position-absolute start-0 top-0 end-0 bottom-0 w-100 gj-cursor-pointer">
                                        <i class="fas fa-file-alt font_14 mute_text"></i>
                                        <p class="font_14 mute_text f_w_500 m-0"><a href="#" id="attach">{{__('ticket.choose files to upload')}}</a></p>
                                    </div>
                                    @if ($errors->has('ticket_file.*'))
                                    <span class="text-danger"
                                        id="error_message">{{ $errors->first('ticket_file.*') }}</span>
                                    @endif
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center w-100">+ {{__('common.create_now')}}</button>
                                </div>
                            </div>
                        </form>
                        <!--/ form  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    (function($){
            "use strict";
            $(document).ready(function(){
                $("#description").summernote({
                    placeholder: "{{__('common.description')}}",
                    tabsize: 2,
                    height: 150,
                    codeviewFilter: true,
			        codeviewIframeFilter: true,
                    disableDragAndDrop:true,
                    toolbar: [
                        ["style", ["style"]],
                        ["font", ["bold", "underline", "clear"]],
                        ["color", ["color"]],
                        ["para", ["ul", "ol", "paragraph"]],
                        ["table", ["table"]],
                        ["insert", ["link", "picture", "video"]],
                        ["view", ["fullscreen", "codeview", "help"]],
                    ],
                });

                $(document).on('change','.attach_file_change', function(event){
                    let unique_id = $(this).data('value');
                    getFileName2($(this).val(),unique_id);
                });
                function getFileName2(value, placeholder){
                    if (value) {
                        var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
                        var filename = value.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        $(placeholder).text(filename);
                    }
                }
            });
        })(jQuery);

</script>
@endpush
