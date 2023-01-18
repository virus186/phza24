@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            var baseUrl = $('#app_base_url').val();
            $(document).ready(function () {

                $(document).on("submit", "#unitForm", function (event) {
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#name_error').text('');
                    let formData = $(this).serializeArray();
                    $.ajax({
                        url: "{{ route('product.units.store') }}",
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                        type: "POST",
                        dataType: "JSON",
                        success: function (response) {
                            toastr.success("{{__('common.added_successfully')}}","{{__('common.success')}}");
                            $("#unitForm").trigger("reset");
                            unitList();
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function (response) {

                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }
                            if (response) {
                                $.each(response.responseJSON.errors, function (key, message) {
                                    @if(isModuleActive('FrontendMultiLang'))
                                    $("#name_{{auth()->user()->lang_code}}_error").html(message[0]);
                                    @else
                                        $("#" + key + "_error").html(message[0]);
                                    @endif
                                });
                            }
                            $('#pre-loader').addClass('d-none');
                        }
                    });
                });
                //
                $(document).on("submit", "#unitEditForm", function (event) {
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#edit_name_error').text('');
                    let id = $(".edit_id").val();
                    let formData = $(this).serializeArray();
                    $.ajax({
                        url: baseUrl + "/products/unit-update/" + id,
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                        type: "POST",
                        dataType: "JSON",
                        success: function (response) {
                            $("#unitEditForm").trigger("reset");
                            $('.edit_div').hide();
                            toastr.success("{{__('common.updated_successfully')}}","{{__('common.success')}}");
                            $('.create_div').show();
                            unitList();
                            $('#pre-loader').addClass('d-none');
                        },
                        error: function (response) {

                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }
                            if (response) {
                                $.each(response.responseJSON.errors, function (key, message) {
                                    @if(isModuleActive('FrontendMultiLang'))
                                    $("#edit_name_{{auth()->user()->lang_code}}_error").html(message[0]);
                                    @else
                                    $("#edit_" + key + "_error").html(message[0]);
                                    @endif
                                });
                            }
                            $('#pre-loader').addClass('d-none');
                        }
                    });
                });

                $(document).on('click', '.delete_unit', function(event){
                    event.preventDefault();
                    let route = $(this).data('value');
                    confirm_modal(route);
                });


                $(document).on("click", ".edit_unit", function () {
                    let unit = $(this).data("value");
                    $('.edit_div').show();
                    $('.edit_div').removeClass("d-none");
                    $('.create_div').hide();
                    @if(isModuleActive('FrontendMultiLang'))
                    if (unit.name != null) {
                        $.each( unit.name, function( key, value ) {
                            $("#name_"+key).val(value);
                        });
                    }else{
                        $(".name").val(unit.translateName);
                    }
                    @else
                    $(".name").val(unit.name);
                    @endif
                    $(".edit_id").val(unit.id);
                    if(unit.status == 1){
                        $('#status_active').prop("checked", true);
                        $('#status_inactive').prop("checked", false);
                    }else{
                        $('#status_active').prop("checked", false);
                        $('#status_inactive').prop("checked", true);
                    }
                });
                function unitList() {
                    $.ajax({
                        url: "{{route("product.units.get_list")}}",
                        type: "GET",
                        dataType: "HTML",
                        success: function (response) {
                            $("#unit_list").html(response);
                            CRMTableThreeReactive();
                        },
                        error: function (error) {
                            toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                        }
                    });
                }

            });
        })(jQuery);
    </script>
@endpush
