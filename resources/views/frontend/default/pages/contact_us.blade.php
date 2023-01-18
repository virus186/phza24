@extends('frontend.default.layouts.app')
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

    @include('frontend.default.partials._breadcrumb')
    <!-- contact info part here -->
    <section class="contact_info padding_top bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="contact_info_text">
                        <h4>{{ $contactContent->subTitle }}</h4>
                        @php echo $contactContent->description; @endphp
                        <a href="mailto:{{ $contactContent->email }}" class="contact_btn">{{ $contactContent->email }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact info part end -->

    <!-- send query part here -->
    <x-contact-form/>
    <!-- send query part end -->


@endsection
@push('scripts')
<script>

    (function($){
        "use strict";

        $(document).ready(function() {

            $('#contactForm').on('submit', function(event) {
                event.preventDefault();
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
                    success: function(response) {
                        toastr.success("{{__('defaultTheme.message_sent_successfully')}}","{{__('common.success')}}");
                        $("#contactBtn").prop('disabled', false);
                        $('#contactBtn').text("{{ __('defaultTheme.send_message') }}");
                        resetErrorData();

                    },
                    error: function(response) {
                        toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        $("#contactBtn").prop('disabled', false);
                        $('#contactBtn').text("{{ __('defaultTheme.send_message') }}");
                        showErrorData(response.responseJSON.errors)

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
        });
    })(jQuery);


</script>
@endpush
