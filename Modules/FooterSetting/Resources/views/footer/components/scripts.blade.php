@push('scripts')
    <script>

        (function($){
            "use strict";
            @if($errors->any())
                $('#CreateModal').modal('show');
            @endif

            $(document).ready(function(){

                $(document).on('submit','#copyright_form', function(event) {
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $("#copyrightBtn").prop('disabled', true);
                    $('#copyrightBtn').text('{{ __('common.updating') }}');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('footerSetting.footer.content-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#copyrightBtn').text('{{__('common.update')}}');
                            $("#copyrightBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            $('#copyrightBtn').text('{{__('common.update')}}');
                            $("#copyrightBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        }
                    });
                });

                $(document).on('submit', '#aboutForm', function(event) {
                    event.preventDefault();
                    $('#error_about_title').text('');
                    var about_title = $('#about_title').val();
                    if(about_title != ''){
                        $("#aboutSectionBtn").prop('disabled', true);
                        $('#aboutSectionBtn').text('{{ __('common.updating') }}');
                        $('#pre-loader').removeClass('d-none');
                        var formElement = $(this).serializeArray()
                        var formData = new FormData();
                        formElement.forEach(element => {
                            formData.append(element.name, element.value);
                        });
                        formData.append('_token', "{{ csrf_token() }}");
                        $.ajax({
                            url: "{{ route('footerSetting.footer.content-update') }}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function(response) {
                                toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                                $('#aboutSectionBtn').text('{{__('common.update')}}');
                                $("#aboutSectionBtn").prop('disabled', false);
                                $('#pre-loader').addClass('d-none');
                            },
                            error: function(response) {
                                $('#aboutSectionBtn').text('{{__('common.update')}}');
                                $("#aboutSectionBtn").prop('disabled', false);
                                $('#pre-loader').addClass('d-none');

                                if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            }
                        });
                    }else{
                        $('#error_about_title').text("{{__('validation.this_field_is_required')}}");
                    }
                });

                $(document).on('submit', '#aboutDescriptionForm', function(event) {
                    event.preventDefault();
                    $("#aboutDescriptionBtn").prop('disabled', true);
                    $('#aboutDescriptionBtn').text('{{ __('common.updating') }}');
                    $('#pre-loader').removeClass('d-none');
                    $('#error_about_description').text('');
                    if($('#about_description').val() == ''){
                        $('#aboutDescriptionBtn').text('{{__('common.update')}}');
                        $("#aboutDescriptionBtn").prop('disabled', false);
                        $('#pre-loader').addClass('d-none');
                        $('#error_about_description').text("{{__('validation.this_field_is_required')}}");
                        return false;
                    }

                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('footerSetting.footer.content-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#aboutDescriptionBtn').text('{{__('common.update')}}');
                            $("#aboutDescriptionBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {
                            $('#aboutDescriptionBtn').text('{{__('common.update')}}');
                            $("#aboutDescriptionBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        }
                    });
                });

                $(document).on('submit', '#companyForm', function(event) {
                    event.preventDefault();
                    $("#companyBtn").prop('disabled', true);
                    $('#companyBtn').text('{{ __('common.updating') }}');
                    $('#pre-loader').removeClass('d-none');
                    $('#error_company_title').text('');
                    if($('#company_title').val() == ''){
                        $('#companyBtn').text('{{__('common.update')}}');
                        $("#companyBtn").prop('disabled', false);
                        $('#pre-loader').addClass('d-none');
                        $('#error_company_title').text("{{__('validation.this_field_is_required')}}");
                        return false;
                    }
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('footerSetting.footer.content-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#companyBtn').text('{{__('common.update')}}');
                            $("#companyBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {
                            $('#companyBtn').text('{{__('common.update')}}');
                            $("#companyBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        }
                    });
                });

                $(document).on('submit','#accountForm', function(event) {
                    event.preventDefault();
                    $("#accountBtn").prop('disabled', true);
                    $('#accountBtn').text('{{ __('common.updating') }}');
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('footerSetting.footer.content-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#accountBtn').text('{{__('common.update')}}');
                            $("#accountBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#accountBtn').text('{{__('common.update')}}');
                            $("#accountBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        }
                    });
                });

                $(document).on('submit', '#serviceForm', function(event) {
                    event.preventDefault();
                    $("#serviceBtn").prop('disabled', true);
                    $('#serviceBtn').text('{{ __('common.updating') }}');
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('footerSetting.footer.content-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#serviceBtn').text('{{__('common.update')}}');
                            $("#serviceBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#serviceBtn').text('{{__('common.update')}}');
                            $("#serviceBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        }
                    });
                });

                $(document).on('click', '.active_section_class', function(event){
                    let id = $(this).data('id');
                    let url = "/footer/footer-setting/tab/" + id;
                    $.ajax({
                            url: url,
                            type: "GET",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(response) {

                            },
                            error: function(response) {

                        }
                    });
                });

                $(document).on('click', '.create_page_btn', function(event){
                    event.preventDefault();
                    let section_id = $(this).data('id');
                    $('#CreateModal').modal('show');
                    $('#section_id').val(section_id);
                });

                $(document).on('change', '.statusChange', function(event){
                    let item = $(this).data('value');
                    var formData = new FormData();
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', item.id);
                    formData.append('status', item.status);
                    $.ajax({
                        url: "{{ route('footerSetting.footer.widget-status') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");

                        }
                    });
                });

                $(document).on('click', '.edit_page', function(event){
                    event.preventDefault();
                    let page = $(this).data('value');
                    $('#editModal').modal('show');
                    $('#widget_name').val(page.name).addClass('has-content');
                    $('#widgetEditId').val(page.id);
                    $("#editCategory").val(page.category);
                    $('#editCategory').niceSelect('update');

                    $("#editPage").val(page.page);
                    $('#editPage').niceSelect('update');

                    if(page.is_static == 1){
                        $('#editPageFieldDiv').css("display","none");
                        $('#editCategoryFieldDiv').removeClass("col-lg-6").addClass("col-lg-12");
                    }else{
                        $('#editPageFieldDiv').css("display","inherit");
                        $('#editCategoryFieldDiv').removeClass("col-lg-12").addClass("col-lg-6");
                    }
                });

                $(document).on('click', '.delete_page', function(event){
                    event.preventDefault();
                    let id = $(this).data('id');
                    $('#deleteItemModal').modal('show');
                    let base_url = "{{url('/')}}";
                    let route = base_url + '/footer/footer-widget-delete/' +id;
                    $('#deleteBtn').attr('href',route);
                });

                $(document).on('change', '#document_file_1', function(){
                    getFileName($(this).val(),'#placeholderFileOneName');
                    imageChangeWithFile($(this)[0],'#blogImgShow');
                });

                $(document).on('submit', '#app_link_form', function(event) {
                    event.preventDefault();
                    $("#appLinkBtn").prop('disabled', true);
                    $('#appLinkBtn').text('{{ __('common.updating') }}');
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    let photo = $('#document_file_1')[0].files[0];
                    formData.append('_token', "{{ csrf_token() }}");
                    if (photo) {
                        formData.append('payment_image', photo)
                    }
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('footerSetting.footer.app_link_other-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('#appLinkBtn').text('{{__('common.update')}}');
                            $("#appLinkBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function(response) {
                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#appLinkBtn').text('{{__('common.update')}}');
                            $("#appLinkBtn").prop('disabled', false);
                            $('#pre-loader').addClass('d-none');
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        }
                    });
                });
                

            });
        })(jQuery);


    </script>
@endpush

