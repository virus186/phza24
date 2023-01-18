@extends('frontend.amazy.layouts.app')
@section('styles')
    <style>
        .mb-15{
            margin-bottom: 15px!important;
        }
        .customer_img input{
            width: 100%;
            background: #fff;
        }
        .send_query .form-group input{
            text-transform: none!important;
        }
    </style>
@endsection
@section('title')
{{$contactContent->mainTitle}}
@endsection
@section('breadcrumb')
    {{ $contactContent->mainTitle }}
@endsection

@section('content')

    <div class="contact_section ">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="contact_map mb_30">
                        <div id="contact-map"></div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-xl-12">
                    <div class="contact_address">
                        <div class="row justify-content-end">
                            <div class="col-lg-6">
                                <div class="contact_box_wrapper">
                                    <div class="contact_wiz_box">
                                        <span class="contact_box_title font_16 f_w_500 d-block lh-1 ">{{__('amazy.Call or WhatsApp')}}:</span>
                                        <h4 class="contact_box_desc mb-0">{{ app('general_setting')->phone }}</h4>
                                    </div>
                                    <div class="contact_wiz_box">
                                        <span class="contact_box_title font_16 f_w_500 d-block lh-1 ">{{__('amazy.Get in touch')}}:</span>
                                        <h4 class="contact_box_desc mb-0">{{ $contactContent->email }}</h4>
                                    </div>
                                    <div class="contact_wiz_box">
                                        <span class="contact_box_title font_16 f_w_500 d-block lh-1 ">{{__('amazy.Social Media')}}:</span>
                                        <div class="contact_link">
                                            <a href="{{ app('general_setting')->facebook }}">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                            <a href="{{ app('general_setting')->twitter }}">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                            <a href="{{ app('general_setting')->linkedin }}">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                            <a href="{{ app('general_setting')->instagram }}">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="contact_wiz_box">
                                        <span class="contact_box_title font_16 f_w_500 d-block lh-1 ">{{__('amazy.Head office')}}:</span>
                                        <h4 class="contact_box_desc mb-0">{{ app('general_setting')->address }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="contact_form_box mb_30">
                                    <div class="contact_info">
                                        <div class="contact_title mb_30">
                                            <h4 class="">{{__('amazy.Get in touch')}}</h4>
                                        </div>
                                    </div>
                                    <form class="form-area contact-form send_query_form" id="contactForm" action="#" name="#" enctype="multipart/form-data">


                                        @if(!empty($row) && !empty($form_data))
                                            @php
                                                $default_field = [];
                                                $custom_field = [];
                                                $custom_file = false;
                                            @endphp
                                            @foreach($form_data as $row)
                                                @php
                                                    if($row->type != 'header' && $row->type !='paragraph'){
                                                        if(property_exists($row,'className') && strpos($row->className, 'default-field') !== false){
                                                            $default_field[] = $row->name;
                                                        }else{
                                                            $custom_field[] = $row->name;
                                                            $custom_file  = true;
                                                        }
                                                        $required = property_exists($row,'required');
                                                        $type = property_exists($row,'subtype') ? $row->subtype : $row->type;
                                                        $placeholder = property_exists($row,'placeholder') ? $row->placeholder : $row->label;
                                                    }
                                                @endphp

                                                    @if($row->type =='header' || $row->type =='paragraph')
                                                        <div class="form-group">
                                                            <{{ $row->subtype }}>{{ $row->label }} </{{ $row->subtype }}>
                                                        </div>
                                                    @elseif($row->type == 'text' || $row->type == 'number' || $row->type == 'email' || $row->type == 'date')

                                                        <div class="col-xl-12">
                                                            <input {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" placeholder="{{$row->label}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{$row->label}}'" class="@error($row->name) is-invalid @enderror primary_line_input style4 mb_10" value="{{ old($row->name) }}" type="{{$type}}">
                                                            @error($row->name)
                                                                <span class="text-danger" >{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                    @elseif($row->type=='select')
                                                        <div class="col-xl-12">
                                                            <select {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" class="form-control amaz_select2 style2 wide mb_30">
                                                                @foreach($row->values as $value)
                                                                    <option value="{{$value->value}}" {{old($row->name) == $value->value? 'selected': ''}}>{{$value->label}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger">{{$errors->first($row->name)}}</span>
                                                        </div>

                                                    @elseif($row->type == 'date')
                                                        <div class="col-xl-12">
                                                            <input {{$required ? 'required' :''}} type="{{$type}}" id="datepicker" class="@error($row->name) form-control is-invalid @enderror" name="{{$row->name}}" value="{{ old($row->name) }}" placeholder="{{$placeholder}}">
                                                            @error($row->name)
                                                            <span class="text-danger" >{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                    @elseif($row->type=='textarea')
                                                        <div class="col-xl-12">

                                                            <textarea class="form-control primary_line_textarea style4 mb_40" {{$required ? 'required' :''}} name="{{$row->name}}" placeholder="{{$placeholder}}" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Write Message here…'" id="{{$row->name}}">{{old($row->name)}}</textarea>

                                                            <span class="text-danger">{{$errors->first($row->name)}}</span>
                                                        </div>

                                                    @elseif($row->type=="radio-group")
                                                        <div class="col-xl-12">
                                                            <label for="">{{ $row->label }}</label>
                                                            <div class="address_type d-flex align-items-center gap_30 flex-wrap mb_5">
                                                                @foreach ($row->values as $value)
                                                                <label class="primary_checkbox style6 d-flex" >
                                                                    <input type="radio" name="{{ $row->name }}" value="{{ $value->value }}">
                                                                    <span class="checkmark mr_10"></span>
                                                                    <span class="label_name f_w_500">{{ $value->label }}</span>
                                                                </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @elseif($row->type=="checkbox-group")
                                                        <div class="col-xl-12 mb_10">
                                                            <label>{{@$row->label}}</label>
                                                            @foreach($row->values as $value)
                                                                <label class="primary_checkbox d-flex mb_30">
                                                                    <input type="checkbox"  name="{{ $row->name }}[]" value="{{ $value->value }}">
                                                                    <span class="checkmark mr_10"></span>
                                                                    <span class="label_name">{{$value->label}}</span>
                                                                </label>
                                                            @endforeach

                                                        </div>

                                                    @elseif($row->type =='file')

                                                        <div class="col-xl-12 customer_img mb_10">
                                                            <input class="{{$custom_file ? 'custom_file' :''}} form-control" accept="image/*" type="{{$type}}" name="{{$row->name}}" id="{{$row->name}}" >
                                                        </div>

                                                    @elseif($row->type =='checkbox')
                                                        <div class="col-md-12 mb-15">
                                                            <div class="checkbox">
                                                                <label class="cs_checkbox">
                                                                    <input id="policyCheck" type="checkbox" checked>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <p>{{$row->label}}</p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                @endforeach
                                                <input type="hidden" name="custom_field" value="{{json_encode($custom_field)}}">

                                            @else
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <input name="name" id="name" placeholder="{{__('defaultTheme.enter_name')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.enter_name')}}'" class="primary_line_input style4 mb_10" type="text">
                                                    <span class="text-danger"  id="error_name"></span>
                                                </div>

                                                <div class="col-xl-12">
                                                    <input name="email" id="email" placeholder="{{__('defaultTheme.enter_email_address')}}" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.enter_email_address')}}'" class="primary_line_input style4 mb_10" type="email">
                                                    <span class="text-danger"  id="error_email"></span>
                                                </div>

                                                <div class="col-xl-12">
                                                    <select name="query_type" id="query_type" class="amaz_select2 style2 wide mb_30 nc_select" >
                                                        @foreach($QueryList as $key => $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="text-danger" id="error_query_type"></span>

                                                <div class="col-xl-12">
                                                    <textarea class="primary_line_textarea style4 mb_40" id="message" name="message" placeholder="{{__('defaultTheme.write_messages')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('defaultTheme.write_messages')}}'"></textarea>
                                                    <span class="text-danger"  id="error_message"></span>
                                                </div>
                                            @endif
                                            @if(env('NOCAPTCHA_FOR_CONTACT') == "true")
                                            <div class="col-12 mb_20">
                                                @if(env('NOCAPTCHA_INVISIBLE') != "true")
                                                <div class="g-recaptcha" data-callback="callback" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"></div>
                                                @else
                                                <div class="g-recaptcha"
                                                    data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"
                                                    data-callback="onSubmit"
                                                    data-size="invisible">
                                                </div>
                                                @endif
                                                <span class="text-danger" id="error_g_recaptcha"></span>
                                            </div>
                                            @endif
                                            <div class="col-lg-12 text-right send_query_btn">
                                                <div class="alert-msg"></div>
                                                <button type="submit" id="contactBtn" class="amaz_primary_btn style2 submit-btn text-center f_w_700 text-uppercase rounded-0 w-100 btn_1" >{{__('defaultTheme.send_message')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{config('app.map_api_key')?config('app.map_api_key'):'AIzaSyDfpGBFn5yRPvJrvAKoGIdj1O1aO9QisgQ'}}"></script>
<script src="{{url('/')}}/public/frontend/amazy/js/map.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>

    (function($){
        "use strict";

        $(document).ready(function() {

            $('#contactForm').on('submit', function(event) {
                event.preventDefault();
                @if(env('NOCAPTCHA_FOR_CONTACT') == "true" )
                    var response = grecaptcha.getResponse();
                    if(response.length == 0){
                        @if(env('NOCAPTCHA_INVISIBLE') != "true")
                        $('#error_g_recaptcha').text("The google recaptcha field is required");
                        return false;
                        @endif
                    }
                    @endif
                $("#contactBtn").prop('disabled', true);
                $('#contactBtn').text('{{ __('common.submitting') }}');

                var formElement = $(this).serializeArray()
                var formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name, element.value);
                });
                if($('.custom_file').length > 0){
                    let photo = $('.custom_file')[0].files[0];
                    if (photo) {
                        formData.append($('.custom_file').attr('name'), photo)
                    }
                }
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: "{{ route('contact.store') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        toastr.success("{{__('defaultTheme.message_sent_successfully')}}","{{__('common.success')}}");
                        $("#contactBtn").prop('disabled', false);
                        $('#contactBtn').text("{{ __('defaultTheme.send_message') }}");
                        resetErrorData();
                    },
                    error: function(data) {
                        toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        $("#contactBtn").prop('disabled', false);
                        $('#contactBtn').text("{{ __('defaultTheme.send_message') }}");
                        showErrorData(data.responseJSON.errors)

                    }
                });
            });

            function showErrorData(errors){
                $('#contactForm #error_name').text(errors.name);
                $('#contactForm #error_email').text(errors.email);
                $('#contactForm #error_query_type').text(errors.query_type);
                $('#contactForm #error_message').text(errors.message);
            }

            function resetErrorData(){
                $('#contactForm')[0].reset();
                $('#contactForm #error_name').text('');
                $('#contactForm #error_email').text('');
                $('#contactForm #error_query_type').text('');
                $('#contactForm #error_message').text('');
            }

            if ($('#contact-map').length != 0) {
                var latitude = "{{ app('general_setting')->latitude }}";
                var longitude = "{{ app('general_setting')->longitude }}";
                google.maps.event.addDomListener(window, 'load', basicmap(parseFloat(latitude),parseFloat(longitude)));
            }

        });
    })(jQuery);


</script>
@endpush
