(function($) {
    "use strict";
    let _token = $('meta[name=_token]').attr('content') ;
    $(document).ready(function(){

        $(document).on('keyup','.page_title',function (){
            let title = $(this).val();
            processSlug(title, '.page_slug');
        });


        $(document).on('submit', '#create_form', function(event){
            event.preventDefault();
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            formData.append('_token',_token);
            resetValidationError();
            $('#pre-loader').removeClass('d-none');
            $('#add_page_modal').modal('hide');
            $.ajax({
                url: $('#store_url').val(),
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    create_form_reset();
                    toastr.success('New Page Create Successfully','Success');
                    resetAfterChange(response.TableData);
                    $('#pre-loader').addClass('d-none');
                },
                error:function(response) {
                    $('#pre-loader').addClass('d-none');
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,trans('common.error'));
                        return false;
                    }
                    showValidationErrors('#create_form',response.responseJSON.errors);
                }
            });
        });
        $(document).on('click', '.edit_row', function(event){
            event.preventDefault();
            let id = $(this).data('id');
            let url =  $('#edit_url').val();
            url = url.replace(':id',id);
            $('#pre-loader').removeClass('d-none');
            $.get(url, function(response){
                if(response){
                    $('#append_html').html(response);
                    $('#edit_page_modal').modal('show');
                }
                $('#pre-loader').addClass('d-none');
            });
        });
        $(document).on('submit', '#update_form', function(event){
            event.preventDefault();
            let formElement = $(this).serializeArray()
            let formData = new FormData();
            formElement.forEach(element => {
                formData.append(element.name,element.value);
            });
            formData.append('_token',_token);
            let id = $('#rowId').val();
            let url = $('#update_url').val();
            url = url.replace(':id',id);
            resetValidationError();
            $('#edit_page_modal').modal('hide');
            $('#pre-loader').removeClass('d-none');
            $.ajax({
                url: url,
                type:"POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success:function(response){
                    resetAfterChange(response.TableData);
                    toastr.success('Page Update Successfully');
                    $('#pre-loader').addClass('d-none');
                },
                error:function(response) {
                    $('#pre-loader').addClass('d-none');
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,trans('common.error'));
                        return false;
                    }
                    showValidationErrors('#update_form',response.responseJSON.errors);
                }
            });
        });
        $(document).on('click','.delete_row',function (event){
            event.preventDefault();
            let id = $(this).data('id');
            $('#delete_item_id').val(id);
            $('#deleteItemModal').modal('show');
        });
        $(document).on('submit', '#item_delete_form', function(event) {
            event.preventDefault();
            $('#deleteItemModal').modal('hide');
            $('#pre-loader').removeClass('d-none');
            var formData = new FormData();
            formData.append('_token', _token);
            formData.append('id', $('#delete_item_id').val());
            $.ajax({
                url:  $('#delete_url').val(),
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    resetAfterChange(response.TableData);
                    toastr.success("Deleted Successfully");
                    $('#pre-loader').addClass('d-none');
                },
                error: function(response) {
                    $('#pre-loader').addClass('d-none');
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,trans('common.error'));
                        return false;
                    }
                }
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
            let formData = new FormData();
            formData.append('_token', _token);
            formData.append('id', id);
            formData.append('status', status);
            $('#pre-loader').removeClass('d-none');
            $.ajax({
                url: $('#status_change_url').val(),
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    toastr.success("Status Updated successfully");
                    $('#pre-loader').removeClass('d-none');
                },
                error: function(response) {
                    $('#pre-loader').removeClass('d-none');
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,trans('common.error'));
                        return false;
                    }
                    toastr.error("Something went wrong");
                }
            });
        });

        function resetAfterChange(TableData){
            $('#lms_data_table').html(TableData);
            CRMTableReactive();
        }
        function create_form_reset(){
            $('#create_form')[0].reset();
        }
        function showValidationErrors(formType, errors){
            $(formType +' #error_title').text(errors.title);
            $(formType +' #error_slug').text(errors.slug);
        }
        function resetValidationError(){
            $('#error_title').html('');
            $('#error_slug').html('');
        }
    });
})(jQuery);
