@push('scripts')

<script src="{{asset(asset_path('backend/vendors/js/icon-picker.js'))}}">
</script>

<script type="text/javascript">
    (function($){
        "use strict";
        $(document).ready(function() {

            $(document).on('mouseover', 'body', function(){
                $('#icon').iconpicker({
                    animation:true
                });
                $('#iconEdit').iconpicker({
                    animation:true
                });

            });

            $(document).on('submit', '#socialLinkCreate', function(event) {
                event.preventDefault();
                $("#social_add_btn").prop('disabled', true);
                $('#social_add_btn').text('{{ __('submitting') }}');
                let formElement = $(this).serializeArray()
                let formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name, element.value);
                });
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('admin.setting.social-link.store') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {

                        toastr.success("{{__('common.added_successfully')}}","{{__('common.success')}}")
                        $('#social_add').modal('hide');
                        $("#social_add_btn").prop('disabled', false);
                        $('#social_add_btn').text('{{ __('common.save') }}');
                        $('#socialLinkCreate')[0].reset();

                        location.reload();
                    },
                    error: function(response) {
                        $('#social_add_btn').text('{{ __('common.save') }}');
                        if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }
                        toastr.error("{{__('common.error_message')}}")
                        showSocialValidationErrors('#socialLinkCreate', response.responseJSON.errors);
                        $("#social_add_btn").prop('disabled', false);
                    }
                });
            });
            $(document).on('submit', '#socialLinkEdit', function(event){
                event.preventDefault();
                $("#social_edit_btn").prop('disabled', true);
                $('#social_edit_btn').text('{{ __('common.updating') }}');
                let formElement = $(this).serializeArray()
                let formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name, element.value);
                });
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                url: "{{ route('admin.setting.social-link.update') }}",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    $('#social_edit').modal('hide');
                    toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}")
                    $("#social_edit_btn").prop('disabled', false);
                    $('#social_edit_btn').text('{{ __('common.update') }}');

                    location.reload();

                },
                error: function(response) {
                    $('#social_edit_btn').text('{{ __('common.update') }}');
                    if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }
                    showSocialValidationErrors('#socialLinkEdit', response.responseJSON
                        .errors);
                    $("#social_edit_btn").prop('disabled', false);
                }
                });

            });

            $(document).on('submit','#item_delete_form', function(event) {
                event.preventDefault();

                $("#dataDeleteBtn").prop('disabled', true);
                $('#dataDeleteBtn').val('{{ __('common.deleting') }}');
                var formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('id', $('#delete_item_id').val());
                let id = $('#delete_item_id').val();
                $.ajax({
                    url: "{{ route('admin.setting.social-link.delete') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {
                        toastr.success("{{__('common.deleted_successfully')}}","{{__('common.success')}}")
                        $('#deleteItemModal').modal('hide');
                        $("#dataDeleteBtn").prop('disabled', false);
                        $('#dataDeleteBtn').val('{{ __('common.delete') }}');
                        location.reload();
                    },
                    error: function(response) {
                        $('#dataDeleteBtn').val('{{ __('common.delete') }}');
                        if(response.responseJSON.error){
                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                        $('#pre-loader').addClass('d-none');
                        return false;
                    }
                        toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                        $("#dataDeleteBtn").prop('disabled', false);
                    }
                });
            });

            function resetValidationError(){
                $('#error_method_name').text('');
                $('#error_phone').text('');
                $('#error_cost').text('');
                $('#error_shipment_time').text('');
                $('#error_thumbnail_logo').text('');
            }

            function showValidationErrors(formType, errors){
                $(formType +' #error_method_name').text(errors.method_name);
                $(formType +' #error_phone').text(errors.phone);
                $(formType +' #error_cost').text(errors.cost);
                $(formType +' #error_shipment_time').text(errors.shipment_time);
                $(formType +' #error_thumbnail_logo').text(errors.method_logo);
            }



            $(document).on('click', '.edit_link', function(event){
                event.preventDefault();
                let item = $(this).data('value');
                socialEdit(item);
            });

            $(document).on('click', '.delete_link', function(event){
                let id = $(this).data('id');
                $('#delete_item_id').val(id);
                $('#deleteItemModal').modal('show');
            });

            $(document).on('click', '#dataDeleteBtn', function(event){
                setTimeout(function(){
                    location.reload();
                }, 2000)
            });

            $(document).on('click', '#add_new_shipping', function(event){
                event.preventDefault();
                $('#social_add').modal('show');
            });

            function socialEdit(item){
                $('#social_edit').modal('show');
                $('#socialLinkEdit #iconEdit').val(item.icon);
                $('#socialLinkEdit #urlEdit').val(item.url);
                $('#socialLinkEdit #id').val(item.id);
                if (item.status == 1) {
                    $('#socialLinkEdit #status_activeEdit').prop("checked", true);
                    $('#socialLinkEdit #status_inactiveEdit').prop("checked", false);
                } else {
                    $('#socialLinkEdit #status_activeEdit').prop("checked", false);
                    $('#socialLinkEdit #status_inactiveEdit').prop("checked", true);
                }
            }

            function resetAfterChange(tableData) {
                $('#socialListDiv').empty();
                $('#socialListDiv').html(tableData);
                CRMTableTwoReactive();
            }


            function showSocialValidationErrors(formType, errors){
                $(formType + ' #error_url').text(errors.url);
                $(formType + ' #error_icon').text(errors.icon);
                $(formType + ' #error_status').text(errors.status);
            }
        });

    })(jQuery);

</script>
@endpush
