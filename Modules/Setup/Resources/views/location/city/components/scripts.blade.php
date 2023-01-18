@push('scripts')
    <script>
        (function($) {
        	"use strict";
                $(document).ready(function(){
                    YajraReActive();

                    $(document).on('submit', '#create_form', function(event){
                        event.preventDefault();
                        $('#pre-loader').removeClass('d-none');

                        let formElement = $(this).serializeArray()
                        let formData = new FormData();
                        formElement.forEach(element => {
                            formData.append(element.name,element.value);
                        });


                        formData.append('_token',"{{ csrf_token() }}");

                        resetValidationError();
                        $.ajax({
                            url: "{{ route('setup.city.store')}}",
                            type:"POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success:function(response){
                                resetAfterChange(response.TableData);
                                create_form_reset();
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                $('#pre-loader').addClass('d-none');


                            },
                            error:function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                                showValidationErrors('#create_form',response.responseJSON.errors);
                                $('#pre-loader').addClass('d-none');
                            }
                        });
                    });

                    $(document).on('submit', '#edit_form', function(event){
                        event.preventDefault();
                        $('#pre-loader').removeClass('d-none');
                        let formElement = $(this).serializeArray()
                        let formData = new FormData();
                        formElement.forEach(element => {
                            formData.append(element.name,element.value);
                        });

                        formData.append('_token',"{{ csrf_token() }}");
                        resetValidationError();
                        $.ajax({
                            url: "{{ route('setup.city.update')}}",
                            type:"POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success:function(response){
                                resetAfterChange(response.TableData);
                                toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                                $('#pre-loader').addClass('d-none');

                                $('#formHtml').html(response.createForm);
                                $('#country').niceSelect();
                                $('#state').niceSelect();

                            },
                            error:function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                                showValidationErrors('#edit_form',response.responseJSON.errors);
                                $('#pre-loader').addClass('d-none');
                            }
                        });
                    });

                    $(document).on('change', '#country', function(event){
                        let country = $('#country').val();
                        $('#pre-loader').removeClass('d-none');
                        if(country){
                            let data = {
                                '_token' : '{{ csrf_token() }}',
                                'country_id' : country
                            }
                            $.post("{{route('setup.city.get-state')}}",data, function(response){

                                if(response){
                                    $('#stateDiv').html(response);
                                    $('#state').niceSelect();
                                }
                                $('#pre-loader').addClass('d-none');
                            });
                        }
                    });

                    $(document).on('click', '.edit_city', function(event){
                        event.preventDefault();
                        $('#pre-loader').removeClass('d-none');
                        let id = $(this).data('id');
                        let base_url = $('#url').val();
                        let url = base_url + '/setup/location/city/edit/' +id;
                        $.get(url, function(response){
                            if(response){
                                $('#formHtml').html(response);
                                $('#country').niceSelect();
                                $('#state').niceSelect();
                            }
                            $('#pre-loader').addClass('d-none');
                        });

                    });

                    $(document).on('change', '.status_change', function(event){
                        event.preventDefault();
                        let status = 0;
                        if($(this).prop('checked')){
                            status = 1;
                        }
                        else{
                            status = 0;
                        }
                        let id = $(this).data('id');
                        $('#pre-loader').removeClass('d-none');
                        let formData = new FormData();
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('id', id);
                        formData.append('status', status);

                        $.ajax({
                            url: "{{ route('setup.city.status') }}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function(response) {
                                toastr.success("{{ __('common.updated_successfully') }}","{{__('common.success')}}");
                                $('#pre-loader').addClass('d-none');
                            },
                            error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                                toastr.error("{{__('common.error_message')}}");
                                $('#pre-loader').addClass('d-none');
                            }
                        });

                    });


                    function YajraReActive(){

                        $('#allData').DataTable({
                            processing: true,
                            serverSide: true,
                            "stateSave": true,
                            ajax: "{{ route('setup.city.getData') }}",
                            columns: [
                                { data: 'DT_RowIndex', name: 'id' },
                                { data: 'name', name: 'name' },
                                { data: 'country', name: 'state.country.name' },
                                { data: 'state', name: 'state.name' },
                                { data: 'status', name: 'status' },
                                { data: 'action', name: 'action' }

                            ],

                            bLengthChange: false,
                            "bDestroy": true,
                            language: {
                                search: "<i class='ti-search'></i>",
                                searchPlaceholder: trans('common.quick_search'),
                                paginate: {
                                    next: "<i class='ti-arrow-right'></i>",
                                    previous: "<i class='ti-arrow-left'></i>"
                                }
                            },
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'copyHtml5',
                                    text: '<i class="fa fa-files-o"></i>',
                                    title: $("#header_title").text(),
                                    titleAttr: 'Copy',
                                    exportOptions: {
                                        columns: ':visible',
                                        columns: ':not(:last-child)',
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class="fa fa-file-excel-o"></i>',
                                    titleAttr: 'Excel',
                                    title: $("#header_title").text(),
                                    margin: [10, 10, 10, 0],
                                    exportOptions: {
                                        columns: ':visible',
                                        columns: ':not(:last-child)',
                                    },

                                },
                                {
                                    extend: 'csvHtml5',
                                    text: '<i class="fa fa-file-text-o"></i>',
                                    titleAttr: 'CSV',
                                    exportOptions: {
                                        columns: ':visible',
                                        columns: ':not(:last-child)',
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    text: '<i class="fa fa-file-pdf-o"></i>',
                                    title: $("#header_title").text(),
                                    titleAttr: 'PDF',
                                    exportOptions: {
                                        columns: ':visible',
                                        columns: ':not(:last-child)',
                                    },
                                    pageSize: 'A4',
                                    margin: [0, 0, 0, 0],
                                    alignment: 'center',
                                    header: true,

                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fa fa-print"></i>',
                                    titleAttr: 'Print',
                                    title: $("#header_title").text(),
                                    exportOptions: {
                                        columns: ':not(:last-child)',
                                    }
                                },
                                {
                                    extend: 'colvis',
                                    text: '<i class="fa fa-columns"></i>',
                                    postfixButtons: ['colvisRestore']
                                }
                            ],
                            columnDefs: [{
                                visible: false
                            }],
                            responsive: true,
                        });

                    }

                    function resetAfterChange(TableData){
                        $('#item_table').html(TableData);
                        YajraReActive();

                    }



                    function create_form_reset(){
                        $('#create_form')[0].reset();

                    }

                    function showValidationErrors(formType, errors){
                        $(formType +' #error_name').text(errors.name);
                        $(formType +' #error_country').text(errors.country);
                        $(formType +' #error_state').text(errors.state);
                    }

                    function resetValidationError(){
                        $('#error_name').html('');
                        $('#error_country').html('');
                        $('#error_state').html('');
                    }

                });
        })(jQuery);
    </script>
@endpush
