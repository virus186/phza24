@push('scripts')
    <script src="{{asset(asset_path('backend/vendors/js/icon-picker.js'))}}"></script>
    <script type="text/javascript">
    (function($) {
    	"use strict";
        var gold_module_check = "{{isModuleActive('GoldPrice')}}";
        $(document).ready(function() {

            if("{{$errors->has('sku.*')}}"){
                toastr.error('SKU must be unique.','Error');
            }
            $('.summernote').summernote({
                height: 200,
                codeviewFilter: true,
			    codeviewIframeFilter: true,
                disableDragAndDrop:true,
                callbacks: {
                    onImageUpload: function (files) {
                        sendFile(files, '.summernote')
                    }
                }
            });
            $('.summernote2').summernote({
                height: 200,
                codeviewFilter: true,
			    codeviewIframeFilter: true,
                disableDragAndDrop:true,
                callbacks: {
                    onImageUpload: function (files) {
                        sendFile(files, '.summernote2')
                    }
                }
            });
            $(".basic").spectrum();

            $('.add_single_variant_row').on('click',function () {

                    $('.variant_row_lists:last').after(`<tr class="variant_row_lists">
                        <td class="pl-0 pb-0 border-0">
                                <input class="placeholder_input" placeholder="-" name="variant_values[]" type="text">
                        </td>
                        <td class="pl-0 pb-0 pr-0 remove border-0">
                            <div class="items_min_icon "><i class="ti-trash"></i></div>
                        </td></tr>`);
            });

            $(document).on('click', '.remove', function () {
                $(this).parents('.variant_row_lists').remove();
            });

            getActiveFieldAttribute();

            $(".prod_type").on('click',function(){
                if($('#product_type').val($(this).val())){
                    getActiveFieldAttribute();
                }
            });

            $(document).on('change', '#stock_manage', function(){
                if($('input[name=product_type]:checked').val() == 1){
                    if($(this).val() == 1){
                        $('#single_stock_div').removeClass('d-none');
                        $('#stock_manage_div').addClass('col-lg-6');
                        $('#stock_manage_div').removeClass('col-lg-12');
                    }else{
                        $('#single_stock_div').addClass('d-none');
                        $('#stock_manage_div').addClass('col-lg-12');
                        $('#stock_manage_div').removeClass('col-lg-6');
                    }
                }else{
                    $('#single_stock_div').addClass('d-none');
                    if($(this).val() == 1){
                        $('.stock_td').removeClass('d-none');
                    }else{
                        $('.stock_td').addClass('d-none');
                    }
                }

            });

            $(document).on('click','.saveBtn',function() {

                $('#error_weight').text('');
                $('#error_length').text('');
                $('#error_breadth').text('');
                $('#error_height').text('');
                @if(isModuleActive('FrontendMultiLang'))
                $('#error_product_name_{{auth()->user()->lang_code}}').text('');
                @else
                $('#error_product_name').text('');
                @endif
                $('#error_category_ids').text('');
                $('#error_unit_type').text('');
                $('#error_minumum_qty').text('');
                $('#error_selling_price').text('');
                $('#error_tax').text('');
                $('#error_discunt').text('');
                $('#error_thumbnail').text('');
                $('#error_shipping_method').text('');
                $('#error_tags').text('');
                var requireMatch = 0;

                let data_value = $(this).data('value');
                $('#save_type').val(data_value);

                @if(isModuleActive('FrontendMultiLang'))
                    if ($("#product_name_{{auth()->user()->lang_code}}").val() === '') {
                        requireMatch = 1;
                        $('#error_product_name_{{auth()->user()->lang_code}}').text("{{ __('product.please_input_product_name') }}");
                    }
                @else
                    if ($("#product_name").val() === '') {
                        requireMatch = 1;
                        $('#error_product_name').text("{{ __('product.please_input_product_name') }}");
                    }
                @endif

                if ($("#category_id").val().length < 1) {
                    requireMatch = 1;
                    $('#error_category_ids').text("{{ __('product.please_select_category') }}");

                }
                if ($("#unit_type_id").val() === null) {
                    requireMatch = 1;
                    $('#error_unit_type').text("{{ __('product.please_select_product_unit') }}");

                }
                if ($("#minimum_order_qty").val() === '') {
                    requireMatch = 1;
                    $('#error_minumum_qty').text("{{ __('product.please_input_minimum_order_qty') }}");

                }
                if ($("#selling_price").val() === '') {
                    requireMatch = 1;
                    $('#error_selling_price').text("{{ __('product.please_input_selling_price') }}");

                }
                if ($("#tax").val() === '') {
                    requireMatch = 1;
                    $('#error_tax').text("{{ __('product.please_input_tax') }}");

                }
                if ($("#discount").val() === '') {
                    requireMatch = 1;
                    $('#error_discunt').text("{{ __('product.please_input_discount_minimum_0') }}");

                }
                    if ($("#tags").val() === '') {
                        requireMatch = 1;
                        $('#error_tags').text("{{ __('product.please_input_tags') }}");

                    }
                if ($('input[name=product_type]:checked').val() === '2' && $(".choice_attribute").val().length === 0) {
                    requireMatch = 1;
                    toastr.warning("{{ __('product.please_select_attribute') }}");

                }
                if (requireMatch == 1) {
                    event.preventDefault();
                }

            });

            getActiveFieldShipping();

            $('#thumbnail_image').on('change', function() {
                // console.log(this.value);
            });
            $('.digital_file_upload_div').hide();


            $(document).on('change', '#choice_attributes', function() {

                var a_id = $(this).val();
                var a_name = $(this).text();
                $('#pre-loader').removeClass('d-none');
                var exsist = $('#attribute_id_'+a_id).length;
                if(exsist > 0){
                    toastr.error("{{__('marketing.this_item_already_added_to_list')}}");
                    $('#pre-loader').addClass('d-none');
                    $('#choice_attributes').val('');
                    $('#choice_attributes').niceSelect('update');
                    return false;
                }
                getAttributeData(a_id);

            });

            function getAttributeData(a_id){
                $.post('{{ route('product.attribute.values') }}', {
                    _token: '{{ csrf_token() }}',
                    id: a_id
                },
                function(data) {
                    $('#customer_choice_options').append(data);
                    $('select').niceSelect();
                    $('#pre-loader').addClass('d-none');
                    $('#choice_attributes').val('');
                    $('#choice_attributes').niceSelect('update');
                    if(gold_module_check){
                        calculateGoldPrice();
                    }
                });
            }
            if($('input[name=choice_no]').length){
                // console.log($('input[name=choice_no]').length);
            }
            // console.log($('input[name=choice_no]').length);

            $(document).on('change', '#tax_type', function(event){
                let id = $(this).val();
                let data = {
                    _token:"{{csrf_token()}}",
                    id:id
                }
                $('#pre-loader').removeClass('d-none');
                $.post("{{route('product.change-gst-group')}}", data, function(response){
                    $('#gst_list_div').html(response);
                    $('#pre-loader').addClass('d-none');
                });
            });

            $(document).on('click', '.attribute_remove', function(){
                let this_data = $(this)[0];
                delete_product_row(this_data);
                $('.sku_combination').html('');
            });
            function delete_product_row(this_data){
                let row = this_data.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }

            $(document).on('change', '#is_physical', function(event){
                var product_type = $('input[name=product_type]:checked').val();

                if (product_type ==1) {
                    if ($('#is_physical').is(":checked"))
                    {
                        shipping_div_show();
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').hide();
                        $('.digital_file_upload_div').hide();
                        $('.weight_single_div').show();
                        weightHeightDivShow()
                    }else{
                        $('#phisical_shipping_div').hide();
                        $('.digital_file_upload_div').show();
                        $('.weight_single_div').hide();
                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }else {
                    if($('#is_physical').is(":checked")){
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').show();
                        $('.variant_digital_div').hide();
                        $('.digital_file_upload_div').hide();
                        shipping_div_show();
                        weightHeightDivShow();

                    }else{
                        $('.variant_physical_div').hide();
                        $('.variant_digital_div').show();
                        $('.digital_file_upload_div').hide();
                        $('#phisical_shipping_div').hide();
                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }

                if ($('#is_physical').is(":checked")){
                    $('#is_physical_prod').val(1);
                }else{
                    $('#is_physical_prod').val(0);
                }
            });

            function weightHeightDivShow(){
                let weight_height_div = $('.weight_height_div');
                weight_height_div.show()
                $("#weight").attr('disabled', false);
                $("#length").attr('disabled', false);
                $("#breadth").attr('disabled', false);
                $("#height").attr('disabled', false);
            }

            function weightHeightDivHide(){
                let weight_height_div = $('.weight_height_div');
                weight_height_div.hide()
                $("#weight").attr('disabled', true);
                $("#length").attr('disabled', true);
                $("#breadth").attr('disabled', true);
                $("#height").attr('disabled', true);
            }



            $(document).on('change', '.variant_digital_file_change', function(event){
                let placeholder_id = $(this).data('name_id');
                getFileName($(this).val(),'#'+placeholder_id);
            });

            $(document).on('change', '#galary_image', function(event){
                galleryImage($(this)[0],'#galler_img_prev');
            });

            $(document).on('change', '#relatedProductAll', function(event){
                relatedProductAll($(this)[0]);
            });

            $(document).on('change', '#upSaleAll', function(event){
                upSaleAll($(this)[0]);
            });

            $(document).on('change', '#crossSaleAll', function(event){
                crossSaleAll($(this)[0]);
            });

            $(document).on('change', '#meta_image', function(event){
                getFileName($('#meta_image').val(),'#meta_image_file');
                imageChangeWithFile($(this)[0],'#MetaImgDiv');
            });

            $(document).on('change', '#thumbnail_image', function(event){
                getFileName($('#thumbnail_image').val(),'#thumbnail_image_file');
                imageChangeWithFile($(this)[0],'#ThumbnailImg')
            });

            $(document).on('change', '#digital_file', function(event){
                getFileName($('#digital_file').val(),'#pdf_place')
            });

            $(document).on('change', '#pdf', function(event){
                getFileName($('#pdf').val(),'#pdf_place1')
            });

            $(document).on('change', '.variant_img_change', function(event){
                let name_id = $(this).data('name_id');
                let img_id = $(this).data('img_id');
                getFileName($(this).val(), name_id);
                imageChangeWithFile($(this)[0], img_id);
            });

            $(document).on('change', '.variant_digital_file_change', function(event){
                let name_id = $(this).data('name_id');
                getFileName($(this).val(),name_id);

            });

            $(document).on('change', '#choice_options', function(event){
                get_combinations();
            });
            get_combinations(true);
            
            $(document).on('click', '#add_new_category', function(event){
                event.preventDefault();
                $('#create_category_modal').modal('show');
            });

            $(document).on('mouseover', 'body', function(){
                $('#icon').iconpicker({
                    animation:true
                });
            });

            $(document).on('click','.in_sub_cat', function(event){
                $(".in_parent_div").toggleClass('d-none');
            });

            $(document).on('change', '#image', function(event){
                getFileName($('#image').val(),'#image_file');
                imageChangeWithFile($(this)[0],'#catImgShow');
            });
            @if(isModuleActive('FrontendMultiLang'))
                $(document).on('keyup', '#category_name{{auth()->user()->lang_code}}', function(event){
                    processSlug($('#category_name{{auth()->user()->lang_code}}').val(), '#category_slug');
                });
            @else
                $(document).on('keyup', '#category_name', function(event){
                    processSlug($('#category_name').val(), '#category_slug');
                });
            @endif
            $(document).on('click', '#add_new_brand', function(event){
                event.preventDefault();
                $('#create_brand_modal').modal('show');
            });

            $(document).on('click', '#add_new_unit', function(event){
                event.preventDefault();
                $('#create_unit_modal').modal('show');
            });

            $(document).on('click', '#add_new_attribute', function(event){
                event.preventDefault();
                $('#create_attribute_modal').modal('show');

            });
            $(document).on('click', '#add_new_shipping', function(event){
                event.preventDefault();
                $('#create_shipping_modal').modal('show');

            });

            $(document).on("change", "#thumbnail_logo", function (event) {
                event.preventDefault();
                imageChangeWithFile($(this)[0],'#shipping_logo');
                getFileName($(this).val(),'#shipping_logo_file');
            });

            $(document).on("change", "#Brand_logo", function (event) {
                event.preventDefault();
                getFileName($(this).val(),'#logo_file');
                imageChangeWithFile($(this)[0],'#logoImg')
            });




            $(document).on('submit', '#add_category_form',  function(event) {
                event.preventDefault();
                $("#pre-loader").removeClass('d-none');
                var formElement = $(this).serializeArray()
                var formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name, element.value);
                });
                //image validaiton
                var validFileExtensions = ['jpeg', 'jpg', 'png'];
                var single_image=document.getElementById('image').files.length;
                if(single_image ==1){
                    var size = (document.getElementById('image').files[0].size / 1024 / 1024).toFixed(2);
                    if (size > 1) {
                       toastr.error("{{__('product.file_must_be_less_than_1_mb')}}","{{__('common.error')}}");
                       return false;
                    }
                    var value=$('#image').val();
                    var type=value.split('.').pop().toLowerCase();
                    if ($.inArray(type, validFileExtensions) == -1) {


                       toastr.error("{{__('product.invalid_type_type_should_be_jpeg_jpg_png')}}","{{__('common.error')}}");
                       return false;
                    }
                    formData.append('image', document.getElementById('image').files[0]);

                }

                formData.append('_token', "{{ csrf_token() }}");

                resetCategoryValidationErrors();

                $.ajax({
                    url: "{{ route('product.category.store') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {

                        $('#category_select_div').html(response.categorySelect);
                        $('#sub_cat_div').html(response.categoryParentList);
                        toastr.success("{{__('common.created_successfully')}}", "{{__('common.success')}}");

                        $('#create_category_modal').modal('hide');
                        $('#add_category_form')[0].reset();
                        dynamicSelect2WithAjax(".category_id", "{{url('/products/get-category-data')}}", "GET");
                        dynamicSelect2WithAjax("#parent_id", "{{url('/products/get-category-data')}}", "GET");
                        $('#sub_cat_div').addClass('d-none');
                        $('.upload_photo_div').removeClass('d-none');

                        $("#pre-loader").addClass('d-none');
                        $('#category_image_div').html(
                        `
                            <label class="primary_input_label" for="">{{__('common.upload_photo')}} ({{__('common.file_less_than_1MB')}})</label>

                            <div class="primary_input mb-25">
                                <div class="primary_file_uploader">
                                  <input class="primary-input" type="text" id="image_file" placeholder="{{__('common.browse_image_file')}}" readonly="">
                                  <button class="" type="button">
                                      <label class="primary-btn small fix-gr-bg" for="image">{{__("common.browse")}} </label>
                                      <input type="file" class="d-none" name="image" id="image">
                                  </button>
                               </div>


                                <span class="text-danger" id="error_category_image"></span>

                            </div>
                        `
                        );
                        $('#category_image_preview_div').html(
                        `
                        <img id="catImgShow" src="{{ showImage('backend/img/default.png') }}" alt="">
                        `
                        );
                    },
                    error: function(response) {
                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }
                        showCategoryValidationErrors('#add_category_form', response.responseJSON.errors);
                        $("#pre-loader").addClass('d-none');
                    }
                });
            });




            $(document).on('submit', '#create_brand_form', function(event){
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');

                resetBrandError();

                let formElement = $(this).serializeArray()
                let formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name,element.value);
                });

                let logo = $('#Brand_logo')[0].files[0];

                if(logo){
                    formData.append('logo',logo);
                }


                formData.append('_token',"{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('product.brand.store')}}",
                    type:"POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(response){
                        $('#brand_select_div').html(response);
                        toastr.success('{{__("product.brand")}} {{__("common.created_successfully")}}');
                        $('#pre-loader').addClass('d-none');
                        $('#create_brand_modal').modal('hide');
                        dynamicSelect2WithAjax(".brand_id", "{{route('product.brands.get-by-ajax')}}", "GET");
                        $('#create_brand_form')[0].reset();
                        $('#brand_logo_img_div').html(
                            `
                            <div class="primary_input mb-25">
                                            <div class="primary_file_uploader">
                                              <input class="primary-input" type="text" id="logo_file" placeholder="{{__('common.browse_image_file')}}" readonly="">
                                              <button class="" type="button">
                                                  <label class="primary-btn small fix-gr-bg" for="Brand_logo">{{__("common.logo")}} </label>
                                                  <input type="file" class="d-none" name="logo" id="Brand_logo">
                                              </button>
                                           </div>


                                            <span class="text-danger" id="error_brand_logo"></span>

                            </div>
                            `
                        );
                        $('#brand_logo_preview_div').html(
                            `<img id="logoImg" src="{{ showImage('backend/img/default.png') }}" alt="">`
                        );
                        $('#brand_status').val(1);
                        $('#brand_status').niceSelect('update');
                        $('#brand_des_div').html(
                            `<div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{__("common.description")}} </label>
                                            <textarea class="summernote" name="description"></textarea>
                                        </div>`

                        );
                        $('.summernote').summernote({
                            height: 200,
                            codeviewFilter: true,
			                codeviewIframeFilter: true
                        });


                    },
                    error:function(response) {
                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }
                        showBrandValidationErrors(response.responseJSON.errors);
                        $('#pre-loader').addClass('d-none');
                    }
                });
            });

            $(document).on('submit', '#create_unit_form', function(event){
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');

                resetUnitError();

                let formElement = $(this).serializeArray()
                let formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name,element.value);
                });

                formData.append('_token',"{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('product.units.store')}}",
                    type:"POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(response){
                        $('#unit_select_div').html(response);
                        toastr.success("{{__('common.added_successfully')}}","{{__('common.success')}}")
                        $('#pre-loader').addClass('d-none');
                        $('#create_unit_modal').modal('hide');
                        $('#unit_type_id').niceSelect();
                        $('#create_unit_form')[0].reset();
                        $('#unit_active_status').prop('checked',true);
                        $('#unit_inactive_status').prop('checked',false);

                    },
                    error:function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                        showUnitValidationErrors(response.responseJSON.errors);
                        $('#pre-loader').addClass('d-none');
                    }
                });
            });

            $(document).on('submit', '#create_attribute_form', function(event){
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');


                let formElement = $(this).serializeArray()
                let formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name,element.value);
                });

                formData.append('_token',"{{ csrf_token() }}");


                $.each(formData, function (key, message) {
                    if (formData[key].name !== 'variant_values[]') {
                        $("#" + "error_attribute_" + formData[key].name).html("");
                    }
                });

                $.ajax({
                    url: "{{ route('product.attribute.store')}}",
                    type:"POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(response){
                        $('#attribute_select_div').html(response);
                        toastr.success('{{__("product.attribute")}} {{__("common.created_successfully")}}');
                        $('#pre-loader').addClass('d-none');
                        $('#create_attribute_modal').modal('hide');
                        $('#choice_attributes').niceSelect();
                        $('#create_attribute_form')[0].reset();
                        $('#customer_choice_options').html('');
                        $('.sku_combination').html('');
                        $('.create_attribute_table tr').slice(1).remove();
                        $('#display_type').val('dropdown');
                        $('#display_type').niceSelect('update');

                    },
                    error:function(response) {

                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                        if (response) {
                            $.each(response.responseJSON.errors, function (key, message) {
                                $("#" +"error_attribute_" + key).html(message[0]);
                            });
                        }
                        $('#pre-loader').addClass('d-none');
                    }
                });
            });


            function showBrandValidationErrors(errors){
                $('#error_brand_name').text(errors.name);
                $('#error_brand_logo').text(errors.logo);
            }
            function resetBrandError(){
                $('#error_brand_name').text('');
                $('#error_brand_logo').text('');
            }

            function showUnitValidationErrors(errors){
                $('#error_unit_name').text(errors.name);
                $('#error_unit_status').text(errors.status);
            }
            function resetUnitError(){
                $('#error_unit_name').text('');
                $('#error_unit_status').text('');
            }

            function resetShippingError(){
                $('#error_shipping_method_name').text('');
                $('#error_shipping_phone').text('');
                $('#error_shipping_shipment_time').text('');
                $('#error_shipping_cost').text('');
                $('#error_shipping_cost').text('');
            }

            function showCategoryValidationErrors(formType, errors) {
                $(formType +' #error_category_name').text(errors.name);
                $(formType +' #error_category_slug').text(errors.slug);
                $(formType +' #error_category_searchable').text(errors.searchable);
                $(formType +' #error_category_icon').text(errors.icon);
                $(formType +' #error_category_status').text(errors.status);
                $(formType +' #error_category_image').text(errors.image);
            }

            function resetCategoryValidationErrors(){
                $('#error_category_name').text('');
                $('#error_category_slug').text('');
                $('#error_category_searchable').text('');
                $('#error_category_icon').text('');
                $('#error_category_status').text('');
                $('#error_category_image').text('');
            }


            //Add more Whole-Sale price for Single Product
            $(document).on('click','.add_single_whole_sale_price',function () {
                $('.whole_sale_price_list:last').after(`<tr class="whole_sale_price_list whole_sale_price_list_child">
                                <td class="pl-0 pb-0 border-0">
                                    <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_0[]">
                                </td>
                                <td class="pl-0 pb-0 border-0">
                                    <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_0[]">
                                </td>
                                <td class="pl-0 pb-0 border-0">
                                    <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_0[]">
                                </td>
                                <td class="pl-0 pb-0 pr-0 remove_whole_sale border-0">
                                    <button type="button" class="btn close style_close_icon">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                </td>
                        </tr>`);
            });

            $(document).on('click', '.remove_whole_sale', function () {
                $(this).parents('.whole_sale_price_list').remove();
            });


            //Add more Whole-Sale price for Variant Product
            $(document).on('click','.add_variant__whole_sale_price',function () {
                var targetModalId = $(this).data('id');
                var incKey = $(this).attr('incKey');

                $(targetModalId).append(`<div class="col-lg-12 variant_whole_sale_price_list">
                            <div class="row mt-2">
                                <div class="col">
                                    <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_${incKey}[]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_${incKey}[]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_${incKey}[]">
                                </div>
                                <div class="col">
                                    <button type="button" class="mt-2 style_plus_icon remove_variant_whole_sale border-0">
                                        <i class="ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`);
            });

            $(document).on('click', '.remove_variant_whole_sale', function () {
                $(this).parents('.variant_whole_sale_price_list').remove();
            });


            //Change product type
            $(document).on('change', '.prod_type', function(){
                var checkWholeSaleM = '{{ (isModuleActive('WholeSale')? 1: null) }}';

                if( $('#single_prod').is(":checked") ){

                    if (checkWholeSaleM==1){
                        $('.whole_sale_info_add').css('display', 'block');
                        $('.whole_sale_price_list_child').remove();

                        $('.whole_sale_info_add tbody').append(`<tr class="whole_sale_price_list">
                            <td class="pl-0 pb-0 border-0">
                                <input type="text" class="form-control primary_input_field" placeholder="Min QTY" name="wholesale_min_qty_0[]">
                            </td>
                            <td class="pl-0 pb-0 border-0">
                                <input type="text" class="form-control primary_input_field" placeholder="Max QTY" name="wholesale_max_qty_0[]">
                            </td>
                            <td class="pl-0 pb-0 border-0">
                                <input type="text" class="form-control primary_input_field" placeholder="Price per piece" name="wholesale_price_0[]">
                            </td>
                        </tr>`);
                    }
                }else{
                    $('.whole_sale_info_add').css('display', 'none');
                    $('.whole_sale_price_list').remove();
                    $('.whole_sale_price_list_child').remove();
                }

            });

            //Append wholesale price in sku table
            $(document).on('click', '.wholesale_p_save_btn', function (){
                var append_w_priceId = $(this).attr('append_w_priceId');
                var w_incKey = $(this).attr('w_incKey');
                $('#append_w_p'+append_w_priceId).empty();

                var wholesale_min_qty_v = $('input[name="wholesale_min_qty_'+w_incKey+'[]"]').map(function(){return $(this).val();}).get();
                var wholesale_max_qty_v = $('input[name="wholesale_max_qty_'+w_incKey+'[]"]').map(function(){return $(this).val();}).get();
                var wholesale_price_v = $('input[name="wholesale_price_'+w_incKey+'[]"]').map(function(){return $(this).val();}).get();

                var w_s_p_list=[];
                for (var w=0; w<wholesale_min_qty_v.length; w++){
                    // console.log(wholesale_min_qty_v[w]);
                    w_s_p_list[w] = "<li>Range:("+wholesale_min_qty_v[w]+"-"+wholesale_max_qty_v[w]+")     $"+wholesale_price_v[w]+"</li>";
                }

                $('#append_w_p'+append_w_priceId).append(w_s_p_list);
                $('#variant_wholesale_price_modal_'+append_w_priceId).modal('toggle');
            });


        });

        function shipping_div_hide()
        {
            $('.shipping_title_div').hide();
            $('.shipping_type_div').hide();
            $('.shipping_cost_div').hide();
            $('#shipping_cost').val(0);
        }

        function shipping_div_show()
        {
            $('.shipping_title_div').show();
            $('.shipping_type_div').show();
            $('.shipping_cost_div').show();
            $('#shipping_cost').val(0);
        }

        function add_more_customer_choice_option(i, name, data) {
            var option_value = '';
            $.each(data.values, function(key, item) {
                if (item.color) {
                    option_value += `<option value="${item.id}">${item.color.name}</option>`
                }
                else {
                    option_value += `<option value="${item.id}">${item.value}</option>`
                }
            });
            $('#customer_choice_options').append(
                '<div class="row"><div class="col-lg-4"><input type="hidden" name="choice_no[]" value="' + i +
                '"><div class="primary_input mb-25"><input class="primary_input_field" width="40%" name="choice[]" type="text" value="' +
                name + '" readonly></div></div><div class="col-lg-8">' +
                '<div class="primary_input mb-25">' +
                '<select name="choice_options_' + i +
                '[]" id="choice_options" class="primary_select mb-15" multiple>' +
                option_value +
                '</select' +
                '</div>' +
                '</div></div>');
            $('select').niceSelect();
        }

        function get_combinations(old = false) {
            let formdata = $('#choice_form').serializeArray();
            if(old){
                formdata.push({name: 'old_sku_price', value: @json(old('selling_price_sku',[]))});
                formdata.push({name: 'old_sku_stock', value: @json(old('sku_stock',[]))});
                formdata.push({name: 'old_sku', value: @json(old('sku',[]))});
            }
            $.ajax({
                type: "POST",
                url: '{{ route('product.sku_combination') }}',
                data: formdata,
                success: function(data) {
                    $('.sku_combination').html(data);
                    if ($('#is_physical').is(":checked")){
                        $('.variant_physical_div').show();
                        $('.variant_digital_div').hide();
                    }else{
                        $('.variant_physical_div').hide();
                        $('.variant_digital_div').show();
                    }
                    if($('#stock_manage').val() == 1){
                        $('.stock_td').removeClass('d-none');
                    }else{
                        $('.stock_td').addClass('d-none');
                    }
                    if(gold_module_check){
                        calculateGoldPrice();
                    }
                }
            });
        }

        function getActiveFieldAttribute() {
            var product_type = $('input[name=product_type]:checked').val();
            if (product_type == 1) {
                $('.attribute_div').hide();

                $('.variant_physical_div').hide();
                $('.customer_choice_options').hide();
                $('.sku_combination').hide();

                $('.sku_single_div').show();
                $('.selling_price_div').show();
                $("#sku_single").removeAttr("disabled");
                $("#purchase_price").removeAttr("disabled");
                $("#selling_price").removeAttr("disabled");
                if($('#stock_manage').val() == 1){
                    $('#single_stock_div').removeClass('d-none');
                    $('#stock_manage_div').addClass('col-lg-6');
                    $('#stock_manage_div').removeClass('col-lg-12');
                }else{
                    $('#single_stock_div').addClass('d-none');
                    $('#stock_manage_div').removeClass('col-lg-6');
                    $('#stock_manage_div').addClass('col-lg-12');
                }
            } else {
                $('.attribute_div').show();
                $('.sku_single_div').hide();
                $('.variant_physical_div').show();
                $('.sku_combination').show();
                $('.customer_choice_options').show();

                $('.selling_price_div').hide();
                $("#sku_single").attr('disabled', true);
                $("#weight_single").attr('disabled', true);
                $("#purchase_price").attr('disabled', true);
                $("#selling_price").attr('disabled', true);
                $('#single_stock_div').addClass('d-none');
                $('#stock_manage_div').removeClass('col-lg-6');
                $('#stock_manage_div').addClass('col-lg-12');

            }
        }

        function getActiveFieldShipping() {
            var shipping_type = $('#shipping_type').val();
            if (shipping_type == 1) {
                $('.shipping_cost_div').hide();
                $('#shipping_cost').val(0);
            } else {
                $('.shipping_cost_div').show();
                $('#shipping_cost').val(0);
            }
        }

        function galleryImage(data, divId) {
            if (data.files) {

                $.each(data.files, function(key, value) {
                    $('#gallery_img_prev').empty();
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#gallery_img_prev').append(
                            `
                                <div class="galary_img_div">
                                    <img class="galaryImg" src="`+ e.target.result +`" alt="">
                                </div>
                            `
                        );

                    };
                    reader.readAsDataURL(value);
                });
            }
        }

        //related product
        let RelatedProduct = new Object();
            RelatedProduct.data = [];
        function relatedProductAll(el){
            if(el.checked){
                $("input[name*='related_product']").prop('checked',true);
                $("input[name*='related_product']:checked").each(function () {
                    RelatedProduct.data.push(this.value);
                });
                $('#related_product_hidden_id').val(JSON.stringify(RelatedProduct.data));
            }else{
                $("input[name*='related_product']").prop('checked',false);
            }
        }

        //up sale
        let UpsaleProduct = new Object();
            UpsaleProduct.data = [];
        function upSaleAll(el){
            if(el.checked){
                $("input[name*='up_sale']").prop('checked',true);
                $("input[name*='up_sale']:checked").each(function () {
                    UpsaleProduct.data.push(this.value);
                });
                $('#upsale_product_hidden_id').val(JSON.stringify(UpsaleProduct.data));
            }else{
                $("input[name*='up_sale']").prop('checked',false);
            }
        }

        //cross sale
        let CrosssaleProduct = new Object();
            CrosssaleProduct.data = [];
        function crossSaleAll(el){
            if(el.checked){
                $("input[name*='cross_sale']").prop('checked',true);
                $("input[name*='cross_sale']:checked").each(function () {
                    CrosssaleProduct.data.push(this.value);
                });
                $('#crosssale_product_hidden_id').val(JSON.stringify(CrosssaleProduct.data));
            }else{
                $("input[name*='cross_sale']").prop('checked',false);
            }
        }

        // tag

        $(document).on('click', '.tag-add', function(e){
            e.preventDefault();
            $('#tags').tagsinput('add', $(this).text());
        });
        @if(isModuleActive('FrontendMultiLang'))
            $(document).on('focusout', '#product_name_{{auth()->user()->lang_code}}', function(){
                // tag get
                $("#tag_show").html('<li></li>');
                var sentence = $(this).val();
                var ENDPOINT = "{{ url('/') }}";
                var url = ENDPOINT + '/setup/getTagBySentence';
                $.get(url,{sentence:sentence},function(result){
                    $("#tag_show").append(result);
                })
            });
        @else
        $(document).on('focusout', '#product_name', function(){
            // tag get
            $("#tag_show").html('<li></li>');
            var sentence = $(this).val();
            var ENDPOINT = "{{ url('/') }}";
            var url = ENDPOINT + '/setup/getTagBySentence';
            $.get(url,{sentence:sentence},function(result){
                $("#tag_show").append(result);
            })
        });
        @endif

        dynamicSelect2WithAjax(".brand_id", "{{route('product.brands.get-by-ajax')}}", "GET");
        dynamicSelect2WithAjax(".category_id", "{{url('/products/get-category-data')}}", "GET");
        dynamicSelect2WithAjax("#parent_id", "{{url('/products/get-category-data')}}", "GET");


        if(gold_module_check){
            $(document).on('change', '#gold_price_id', function(){
                calculateGoldPrice();
            });
            $(document).on('keyup', '#making_charge', function(){
                calculateGoldPrice();
            });
            $(document).on('keyup', '#weight', function(){
                calculateGoldPrice();
            });
            $(document).on('change', 'input[name=auto_update_required]', function(){
                calculateGoldPrice();
            });
        }

        function calculateGoldPrice(){
            if($('input[name=auto_update_required]:checked').val() == 1){
                var weight = $('#weight').val();
                var making_charge = $('#making_charge').val();
                var gold_price = $('#gold_price_id').find(':selected').data('price');
                if(weight == ''){
                    weight = 0;
                }
                if(making_charge == ''){
                    making_charge = 0;
                }
                if(gold_price == ''){
                    gold_price = 0;
                }
                var selling_price = (parseFloat(gold_price) + parseFloat(making_charge)) * parseFloat(weight);
                $('.selling_price').val(selling_price);
            }
        }

        $(document).on('change', '.related_product_checked', function (event) {
            event.preventDefault();
            var id = $(this).val();
            if ($(this).is(":checked") == true) { 
                RelatedProduct.data.push(id);
                $('#related_product_hidden_id').val(JSON.stringify(RelatedProduct.data));
            }
            else{
                RelatedProduct.data = jQuery.grep(RelatedProduct.data, function(value) {
                return value != parseInt(id);
                });
                $('#related_product_hidden_id').val(JSON.stringify(RelatedProduct.data));
            } 
        });
        $(document).on('change', '.upsale_product_checked', function (event) {
            event.preventDefault();
            var id = $(this).val();
            if ($(this).is(":checked") == true) { 
                UpsaleProduct.data.push(id);
                $('#upsale_product_hidden_id').val(JSON.stringify(UpsaleProduct.data));
            }
            else{
                UpsaleProduct.data = jQuery.grep(UpsaleProduct.data, function(value) {
                return value != parseInt(id);
                });
                $('#upsale_product_hidden_id').val(JSON.stringify(UpsaleProduct.data));
            } 
        });
        $(document).on('change', '.crosssale_product_checked', function (event) {
            event.preventDefault();
            var id = $(this).val();
            if ($(this).is(":checked") == true) { 
                CrosssaleProduct.data.push(id);
                $('#crosssale_product_hidden_id').val(JSON.stringify(CrosssaleProduct.data));
            }
            else{
                CrosssaleProduct.data = jQuery.grep(CrosssaleProduct.data, function(value) {
                return value != parseInt(id);
                });
                $('#crosssale_product_hidden_id').val(JSON.stringify(CrosssaleProduct.data));
            } 
        });

        $(document).on('click', '#related_product .pagination a', function (event) {
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');
                var page = $(this).attr('href').split('page=')[1];
                related_products(page);
            }); 
            function related_products(page) {
                var search = $('#rsearch_products').val();
                $.ajax({
                    url: "/products/related-products-pagination?page=" + page,
                    type: "GET",
                    data: {
                        "search": search,
                        "spage": 'create',
                    },
                    success: function (data) {
                        $('#related_product').html(data);
                        $("input[name*='related_product']").each(function () {
                            if (RelatedProduct.data.includes(this.value)) {
                                $(this).prop('checked',true)
                            }else{
                                $(this).prop('checked',false)
                            } 
                        });
                        $('#pre-loader').addClass('d-none');
                    }
                });
            }
        $(document).on('keyup', '#rsearch_products', function(event){
            event.preventDefault();
                var search = $(this).val();
                $.ajax({
                    url: "{{route('product.related.product')}}",
                    type: "GET",
                    data: {
                        "search": search,
                        "spage": 'create',
                        "ids": $('#related_product_hidden_id').val(),
                        "type": 'empty',
                    },
                    success: function (data) {
                        $('#related_product').html(data);
                        $("input[name*='related_product']").each(function () {
                            if (RelatedProduct.data.includes(this.value)) {
                                $(this).prop('checked',true)
                            }else{
                                $(this).prop('checked',false)
                            } 
                        });
                        if (data.status == 'nothing_found') {
                            $('#related_product').html(
                                '<h4 class="text-danger mt-15 text-center">' + 'Nothing Found!' + '</h4>');
                        }
                    }
                });
        });
        $(document).on('click', '#upsale_products .pagination a', function (event) {
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');
                var page = $(this).attr('href').split('page=')[1];
                upsale_products(page);
            }); 
            function upsale_products(page) {
                var search = $('#upsale_search_products').val();
                $.ajax({
                    url: "/products/upsale-products-pagination?page=" + page,
                    type: "GET",
                    data: {
                        "search": search,
                        "spage": 'create',
                    },
                    success: function (data) {
                        $('#upsale_products').html(data);
                        $("input[name*='up_sale']").each(function () {
                            if (UpsaleProduct.data.includes(this.value)) {
                                $(this).prop('checked',true)
                            }else{
                                $(this).prop('checked',false)
                            } 
                        });
                        $('#pre-loader').addClass('d-none');
                    }
                });
            }
        $(document).on('keyup', '#upsale_search_products', function(event){
            event.preventDefault();
                var search = $(this).val();
                $.ajax({
                    url: "{{route('product.upsale.product')}}",
                    type: "GET",
                    data: {
                        "search": search,
                        "spage": 'create',
                        "ids": $('#upsale_product_hidden_id').val(),
                        "type": 'empty',
                    },
                    success: function (data) {
                        $('#upsale_products').html(data);
                        $("input[name*='up_sale']").each(function () {
                            if (UpsaleProduct.data.includes(this.value)) {
                                $(this).prop('checked',true)
                            }else{
                                $(this).prop('checked',false)
                            } 
                        });
                        if (data.status == 'nothing_found') {
                            $('#upsale_products').html(
                                '<h4 class="text-danger mt-15 text-center">' + 'Nothing Found!' + '</h4>');
                        }
                    }
                });
        });
        $(document).on('click', '#crosssale_products .pagination a', function (event) {
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');
                var page = $(this).attr('href').split('page=')[1];
                crosssale_products(page);
            }); 
            function crosssale_products(page) {
                var search = $('#crosssale_search_products').val();
                $.ajax({
                    url: "/products/crosssale-products-pagination?page=" + page,
                    type: "GET",
                    data: {
                        "search": search,
                        "spage": 'create',
                    },
                    success: function (data) {
                        $('#crosssale_products').html(data);
                        $("input[name*='cross_sale']").each(function () {
                            if (CrosssaleProduct.data.includes(this.value)) {
                                $(this).prop('checked',true)
                            }else{
                                $(this).prop('checked',false)
                            } 
                        });
                        $('#pre-loader').addClass('d-none');
                    }
                });
            }
        $(document).on('keyup', '#crosssale_search_products', function(event){
            event.preventDefault();
                var search = $(this).val();
                $.ajax({
                    url: "{{route('product.crosssale.product')}}",
                    type: "GET",
                    data: {
                        "search": search,
                        "spage": 'create',
                        "ids": $('#crosssale_product_hidden_id').val(),
                        "type": 'empty',
                    },
                    success: function (data) {
                        $('#crosssale_products').html(data);
                        $("input[name*='cross_sale']").each(function () {
                            if (CrosssaleProduct.data.includes(this.value)) {
                                $(this).prop('checked',true)
                            }else{
                                $(this).prop('checked',false)
                            } 
                        });
                        if (data.status == 'nothing_found') {
                            $('#crosssale_products').html(
                                '<h4 class="text-danger mt-15 text-center">' + 'Nothing Found!' + '</h4>');
                        }
                    }
                });
        });
        @if(isModuleActive('FrontendMultiLang'))
            $(document).on('click', '.default_lang', function(event){
                var lang = $(this).data('id');
                if (lang == "{{auth()->user()->lang_code}}") {  
                    $('#default_lang_{{auth()->user()->lang_code}}').removeClass('d-none');
                }
            });
            if ("{{auth()->user()->lang_code}}") {  
                    $('#default_lang_{{auth()->user()->lang_code}}').removeClass('d-none');
            }
        @endif

    })(jQuery);
    (function ($) {
        "use strict";
        $(document).ready(function () {
            $('.pagination-container [aria-label="« Previous"] .page-link').html("<i class='fa fa-angle-left'></i>");
            $('.pagination-container .page-link[aria-label="« Previous"]').html("<i class='fa fa-angle-left'></i>");
            $('.pagination-container .page-link[aria-label="Next »"]').html("<i class='fa fa-angle-right'></i>");
        });
    })(jQuery);

    </script>
@endpush
