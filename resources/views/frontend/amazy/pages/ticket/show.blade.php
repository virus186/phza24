@extends('frontend.amazy.layouts.app')
@section('title')
    {{ __('ticket.ticket') }}
@endsection
@section('content')
<div class="amazy_dashboard_area dashboard_bg section_spacing6">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-3">
                @include('frontend.amazy.pages.profile.partials._menu')
            </div>
            @php
                $user = auth()->user();
            @endphp
            <div class="col-xl-6 col-lg-6">
                <div class="dashboard_white_box style4 bg-white mb_25">
                    <h3 class="font_20 f_w_700 lh-base mb_10 max_450px">#{{$ticket->reference_no }} - {{$ticket->subject}}</h3>
                    <p class="font_14 f_w_400 mb_25">{{date_format($ticket->created_at, 'F j, Y g:i a')}}</p>
                    <div class="ticket_view_box theme_border gray_color_1 radius_5px mb_10">
                        <div class="ticket_view_box_head d-flex align-items-center mb_20 flex-wrap gap_10">
                            <div class="thicket_view_profile d-flex align-items-center gap_15 flex-fill">
                                <div class="thumb">
                                    <img src="{{showImage($user->avatar?$user->avatar:'frontend/default/img/avatar.jpg')}}" alt="{{textLimit($user->first_name.' '.$user->last_name, 20)}}" title="{{textLimit($user->first_name.' '.$user->last_name, 20)}}" class="img-fluid">
                                </div>
                                <h4 class="font_18 f_w_700 m-0">{{textLimit($user->first_name.' '.$user->last_name, 20)}}</h4>
                            </div>
                            <span class="font_14 f_w_400 mute_text">{{date_format($ticket->created_at, 'd/m/Y g:i a')}}</span>
                        </div>
                        <div class="ticket_view_box_body">
                            @php echo $ticket->description; @endphp
                        </div>
                    </div>
                    @foreach($ticket->messages as $key => $message)
                        <div class="ticket_view_box theme_border gray_color_1 radius_5px mb_20">
                            <div class="ticket_view_box_head d-flex align-items-center mb_20 flex-wrap">
                                <div class="thicket_view_profile d-flex align-items-center gap_15 flex-fill">
                                    <div class="thumb">
                                        <img src="{{ showImage($message->user->avatar?$message->user->avatar:'frontend/default/img/avatar.jpg') }}" alt="{{textLimit($message->user->first_name.' '.$message->user->last_name,20)}}" title="{{textLimit($message->user->first_name.' '.$message->user->last_name,20)}}" class="img-fluid">
                                    </div>
                                    <h4 class="font_18 f_w_700 m-0">{{textLimit($message->user->first_name.' '.$message->user->last_name,20)}}</h4>
                                </div>
                                <span class="font_14 f_w_400 mute_text">{{date_format($message->created_at, 'd/m/Y g:i a')}}</span>
                            </div>
                            <div class="ticket_view_box_body">
                                @php echo $message->text; @endphp
                                @if ($message->attachMsgFile->count() > 0)
                                    @foreach($message->attachMsgFile as $key => $file)
                                        <a href="{{ URL::to('/') }}/{{ asset_path($file->url) }}" download class="file_name amaz_badge_btn6 d-inline-flex align-items-center gap_12">
                                            <i class="fas fa-file-alt"></i>
                                            <span class="lh-1">{{  $key+1 }} .
                                                {{ strlen($file->name) > 20 ? substr($file->name, 0,20) . '...' . $file->type : $file->name }}</span>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex align-items-center justify-content-end mb_20">
                        <button data-bs-toggle="collapse" data-bs-target="#replay_toggler" aria-expanded="false" aria-controls="replay_toggler" id="replay_box_toggler" class="amaz_primary_btn style2 " type="button">{{__('common.reply')}}</button>
                    </div>
                    <div  class="collapse" id="replay_toggler">
                        <form id="replyForm" action="{{ route('ticket.message') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="replay_form_boxInner" class="row ">
                                <div class="col-12 mb_20">
                                    <label class="primary_label2 style2 ">{{__('common.description')}} <span>*</span></label>
                                    <textarea id="description" name="text" class="primary_textarea4 radius_5px mb_25"></textarea>
                                    @if ($errors->has('text'))
                                        <span class="text-danger" id="error_message">{{ $errors->first('text') }}</span>
                                    @endif
                                </div>
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
                                <input type="hidden" name="type" value="0" />
                                <div class="col-12">
                                    <div class="tkt_uploader d-flex align-items-center gap_15 position-relative mb_20  justify-content-center">
                                    <input type="file" name="ticket_file[]" id="ticket_file" data-value="#attach" class="attach_file_change position-absolute start-0 top-0 end-0 bottom-0 w-100 gj-cursor-pointer">
                                        <i class="fas fa-file-alt font_14 mute_text"></i>
                                        <p class="font_14 mute_text f_w_500 m-0" id="attach"><a href="#">{{__('ticket.choose files to upload')}}</a></p>
                                    </div>
                                    @if ($errors->has('ticket_file.*'))
                                        <span class="text-danger" id="error_message">{{ $errors->first('ticket_file.*') }}</span>
                                    @endif
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button class="amaz_primary_btn style2 rounded-0  text-uppercase  text-center w-100">+ {{__('ticket.Reply now')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
            <div class="col-xl-3 col-lg-3">
                <div class="dashboard_white_box style5 bg-white mb_25">
                    <div class="thumb">
                        <img class="img-fluid mb_20" src="{{showImage($user->avatar?$user->avatar:'frontend/default/img/avatar.jpg')}}" alt="{{textLimit($user->first_name.' '.$user->last_name,20)}}" title="{{textLimit($user->first_name.' '.$user->last_name,20)}}">
                    </div>
                    <h3 class="font_16 f_w_700 mb_3">{{textLimit($user->first_name.' '.$user->last_name,20)}}</h3>
                    <p class="font_14 f_w_400 mb_15">{{__('common.customer')}}</p>
                    <div class="user_wrapper_lists mb_20">
                        <div class="user_wrapper_list d-flex align-items-center align-items-center">
                            <div class="user_wrapper_left d-flex align-items-center justify-content-between">
                                <span class="font_14 f_w_500">@lang('common.status')</span>
                                <span class="font_14 f_w_500">:</span>
                            </div>
                            <a href="#" class="amaz_badge_btn7 d-inline-flex align-items-center">
                                {{@$ticket->status->name}}
                            </a>
                        </div>
                        <div class="user_wrapper_list d-flex align-items-center align-items-center">
                            <div class="user_wrapper_left d-flex align-items-center justify-content-between">
                                <span class="font_14 f_w_500">@lang('ticket.priority')</span>
                                <span class="font_14 f_w_500">:</span>
                            </div>
                            <a href="#" class="amaz_badge_btn7 d-inline-flex align-items-center">
                                {{@$ticket->priority->name}}
                            </a>
                        </div>
                        <div class="user_wrapper_list d-flex align-items-center align-items-center">
                            <div class="user_wrapper_left d-flex align-items-center justify-content-between">
                                <span class="font_14 f_w_500">@lang('common.category')</span>
                                <span class="font_14 f_w_500">:</span>
                            </div>
                            <a href="#" class="amaz_badge_btn7 d-inline-flex align-items-center">
                                {{@$ticket->category->name}}
                            </a>
                        </div>
                    </div>
                    <p class="font_14 f_w_500 mb_3">@lang('ticket.last_update') :</p>
                    <h3 class="font_14 f_w_500 m-0">{{ date_format($ticket->updated_at, "F j, Y ")}} {{__('ticket.at')}} {{date_format($ticket->updated_at, "g:i a")}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($errors->any())
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                replyCheck();
            });
            function replyCheck(){
                $('#replay_toggler').addClass('show');
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#replyForm").offset().top
                }, 1500);
            }
        })(jQuery);
    </script>
@endif

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