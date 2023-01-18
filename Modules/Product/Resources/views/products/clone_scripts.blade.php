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
                getActiveFieldAttribute();
                $(document).on('click','.prod_type',function(){
                    if($('#product_type').val($(this).val())){
                        getActiveFieldAttribute();
                    }
                });
                getActiveFieldShipping();
                get_combinations();

            });

            $(document).on('change',"#choice_options",function(){
                get_combinations();
            });

            $(document).on('change', '#stock_manage', function(){
                if($('#product_type').val() == 1){
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

            $(document).on('change',"#meta_image",function(){
                getFileName($(this).val(),'#meta_image_file');
                imageChangeWithFile($(this)[0],'#MetaImgDiv');
            });

            $(document).on('change',"#thumbnail_image",function(){
                getFileName($(this).val(),'#thumbnail_image_file');
                imageChangeWithFile($(this)[0],'#ThumbnailImg');
            });

            $(document).on('change', '.variant_img_change', function(event){
                let name_id = $(this).data('name_id');
                let img_id = $(this).data('img_id');
                getFileName($(this).val(), name_id);
                imageChangeWithFile($(this)[0], img_id);
            });

            $(document).on('change',"#pdf",function(){
                getFileName($(this).val(),'#pdf_place');
            });
            $(document).on('change', '#pdf_file', function(event){
                getFileName($('#pdf_file').val(),'#pdf_place1')
            });

            $(document).on('change','#galary_image', function(event){
                galleryImage($(this)[0],'#galler_img_prev');
            });
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
                // $('.upload_photo_div').toggleClass('d-none');
            });

            $(document).on('change', '#image', function(event){
                getFileName($('#image').val(),'#image_file');
                imageChangeWithFile($(this)[0],'#catImgShow');
            });

            $(document).on('keyup', '#category_name', function(event){
                processSlug($('#category_name').val(), '#category_slug');
            });


            $(document).on('click', '#add_new_brand', function(event){
                event.preventDefault();
                $('#create_brand_modal').modal('show');
            });

            $(document).on('click', '#add_new_unit', function(event){
                event.preventDefault();
                $('#create_unit_modal').modal('show');
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
                        toastr.success("{{__('common.added_successfully')}}","{{__('common.success')}}")
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
                        toastr.success('{{__("product.unit")}} {{__("common.created_successfully")}}');
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

            $(document).on('submit', '#create_shipping_form', function(event){
                event.preventDefault();
                $('#pre-loader').removeClass('d-none');

                let shipment_time = $('#shipment_time').val();
                $('#error_shipping_shipment_time').text('');

                let userKeyRegExp1 = /^[0-9]\-[0-9] [a-z]{4}?$/;
                let userKeyRegExp2 = /^[0-9]\-[0-9]{2}\ [a-z]{4}?$/;
                let userKeyRegExp3 = /^[0-9]\-[0-9]{3}\ [a-z]{4}?$/;
                let userKeyRegExp4 = /^[0-9]{2}\-[0-9]{2}\ [a-z]{4}?$/;
                let userKeyRegExp5 = /^[0-9]{2}\-[0-9]{3}\ [a-z]{4}?$/;
                let userKeyRegExp6 = /^[0-9]{3}\-[0-9]{3}\ [a-z]{4}?$/;

                let userKeyRegExp7 = /^[0-9]\-[0-9]\ [a-z]{3}?$/;
                let userKeyRegExp8 = /^[0-9]\-[0-9]{2}\ [a-z]{3}?$/;
                let userKeyRegExp9 = /^[0-9]\-[0-9]{3}\ [a-z]{3}?$/;
                let userKeyRegExp10 = /^[0-9]{2}\-[0-9]{2}\ [a-z]{3}?$/;
                let userKeyRegExp11 = /^[0-9]{2}\-[0-9]{3}\ [a-z]{3}?$/;
                let userKeyRegExp12 = /^[0-9]{3}\-[0-9]{3}\ [a-z]{3}?$/;

                let valid1 = userKeyRegExp1.test(shipment_time);
                let valid2 = userKeyRegExp2.test(shipment_time);
                let valid3 = userKeyRegExp3.test(shipment_time);
                let valid4 = userKeyRegExp4.test(shipment_time);
                let valid5 = userKeyRegExp5.test(shipment_time);
                let valid6 = userKeyRegExp6.test(shipment_time);
                let valid7 = userKeyRegExp7.test(shipment_time);
                let valid8 = userKeyRegExp8.test(shipment_time);
                let valid9 = userKeyRegExp9.test(shipment_time);
                let valid10 = userKeyRegExp10.test(shipment_time);
                let valid11 = userKeyRegExp11.test(shipment_time);
                let valid12 = userKeyRegExp12.test(shipment_time);

                if(valid1 !=false || valid2!=false || valid3!=false || valid4!=false || valid5!=false ||
                 valid6!=false || valid7!=false || valid8!=false || valid9!=false || valid10!=false || valid11!=false || valid12!=false){
                    let data1 = shipment_time.split(" ");

                    if(data1[1] == 'days' || data1[1] == 'hrs'){

                    }else{
                        $('#pre-loader').addClass('d-none');
                        $('#error_shipping_shipment_time').text('Format must be like 3-5 days or 3-5 hrs');
                        return false;
                    }

                }
                else{
                    $('#pre-loader').addClass('d-none');
                    $('#error_shipping_shipment_time').text('Format must be like 3-5 days or 3-5 hrs');
                    return false;
                }

                $('#error_shipping_shipment_time').text('');

                let formElement = $(this).serializeArray()
                let formData = new FormData();
                formElement.forEach(element => {
                    formData.append(element.name,element.value);
                });

                let method_logo = $('#thumbnail_logo')[0].files[0];

                if(method_logo){
                    formData.append('method_logo',method_logo);
                }

                resetShippingError();


                formData.append('_token',"{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('shipping_methods.store')}}",
                    type:"POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success:function(response){
                        $('#shipping_method_div').html(response);
                        toastr.success('{{__("common.created_successfully")}}');
                        $('#pre-loader').addClass('d-none');
                        $('#create_shipping_modal').modal('hide');
                        $('#shipping_methods').niceSelect();
                        $('#create_shipping_form')[0].reset();
                        $('#method_logo_img_div').html(
                            `
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('shipping.logo') }} </label>
                                        <div class="primary_file_uploader">
                                            <input class="primary-input" type="text" id="logo_file" placeholder="{{ __('shipping.logo') }}" readonly="">
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="thumbnail_logo">{{ __('product.Browse') }} </label>

                                                <input type="file" class="d-none" name="method_logo" id="thumbnail_logo">
                                            </button>
                                            <span class="text-danger" id="error_shipping_thumbnail_logo"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <img id="shipping_logo" class="" src="{{ showImage('backend/img/default.png') }}" alt="">
                                </div>
                            </div>
                            `
                        );


                    },
                    error:function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                        $.each(response.responseJSON.errors, function (key, message) {
                                $("#" +"error_shipping_" + key).html(message[0]);
                            });
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
                if ($('#product_type').val() === '2' && $(".choice_attribute").val().length === 0) {
                    requireMatch = 1;
                    toastr.warning("{{ __('product.please_select_attribute') }}");

                }
                if (requireMatch == 1) {
                    event.preventDefault();
                }
            });


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

            });

            $(document).on('click', '.attribute_remove', function(){
                let this_data = $(this)[0];
                delete_product_row(this_data);
                $('.sku_combination').html('');
            });

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

            function delete_product_row(this_data){
                let row = this_data.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }

            if ($('#is_physical').is(":checked")){
                weightHeightDivShow();
            }else {
                weightHeightDivHide();
            }

            $(document).on('change', '#is_physical', function(event){
                var product_type = $('#product_type').val();
                if (product_type ==1) {
                    if ($('#is_physical').is(":checked"))
                    {
                        shipping_div_show();
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').hide();
                        $('.digital_file_upload_div_edit').hide();
                        weightHeightDivShow();
                    }else{
                        $('#phisical_shipping_div').hide();
                        $('.digital_file_upload_div_edit').show();
                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }else {
                    if($('#is_physical').is(":checked")){
                        $('#phisical_shipping_div').show();
                        $('.variant_physical_div').show();
                        $('.variant_digital_div').hide();
                        $('.digital_file_upload_div_edit').hide();
                        shipping_div_show();
                        weightHeightDivShow();
                    }else{
                        $('.variant_physical_div').hide();
                        $('.variant_digital_div').show();
                        $('.digital_file_upload_div_edit').hide();
                        $('#phisical_shipping_div').hide();
                        shipping_div_hide();
                        weightHeightDivHide();
                    }
                }

                if ($('#is_physical').is(":checked")){
                    $('#is_physical_prod').val(1);
                    $('.shipping_title_div').show();
                    $('#shipping_method_div').show();
                }else{
                    $('#is_physical_prod').val(0);
                    $('.shipping_title_div').hide();
                    $('#shipping_method_div').hide();
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



            var ENDPOINT = "{{ url('/') }}";
            var Rpage = 0;
            var Upage = 0;
            var Cpage = 0;
            $(".lodeMoreRelatedSale").on('click',function() {
                event.preventDefault();
                Rpage++;
                var new_url = '/products/get-related-product-for-admin?page=';
                var tbl_name = "#tablecontentsrelatedProduct";
                infinteLoadMore(Rpage, new_url, tbl_name)
            });
            $(".lodeMoreUpSale").on('click',function() {
                event.preventDefault();
                Upage++;
                var new_url = '/products/get-upsale-product-for-admin?page=';
                var tbl_name = "#tablecontentsupSaleAll";
                infinteLoadMore(Upage, new_url, tbl_name)
            });
            $(".lodeMoreCrossSale").on('click',function() {
                event.preventDefault();
                Cpage++;
                var new_url = '/products/get-cross-sale-product-for-admin?page=';
                var tbl_name = "#tablecontentscrossSaleAll";
                infinteLoadMore(Cpage, new_url, tbl_name)
            });
            function infinteLoadMore(page, new_url, tbl_name) {
                $('#pre-loader').removeClass('d-none');
                $.ajax({
                        url: ENDPOINT + new_url + page,
                        datatype: "html",
                        type: "get",
                        beforeSend: function () {
                            $('.auto-load').show();
                        }
                })
                .done(function (response) {
                    $('#pre-loader').addClass('d-none');
                    if (response.length == 0) {
                            toastr.warning("{{ __('product.no_more_data_to_show') }}");
                            return;
                        }
                        $('.auto-load').hide();
                        $(tbl_name).append(response);
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        $('#pre-loader').addClass('d-none');
                        console.log('Server error occured');
                });
            }

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

            function get_combinations(el)
            {
                $.ajax({
                    type:"POST",
                    url:'{{ route('product.sku_combination_edit') }}',
                    data:$('#choice_form').serialize(),
                    headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                    success: function(data){
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
                        Amaz.uploader.previewGenerate();
                        if(gold_module_check){
                            calculateGoldPrice();
                        }
                    }
                });
            }

            function getActiveFieldAttribute()
            {
                var product_type = $('#product_type').val();
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
                }else {
                    $('.attribute_div').show();
                    $('.sku_single_div').hide();

                    $('.variant_physical_div').show();
                    $('.sku_combination').show();
                    $('.customer_choice_options').show();

                    $('.selling_price_div').hide();
                    $("#sku_single").attr('disabled', true);
                    $("#purchase_price").attr('disabled', true);
                    $("#selling_price").attr('disabled', true);

                    $('#single_stock_div').addClass('d-none');
                    $('#stock_manage_div').removeClass('col-lg-6');
                    $('#stock_manage_div').addClass('col-lg-12');
                }
            }

            function getActiveFieldShipping()
            {
                var shipping_type = $('#shipping_type').val();
                if (shipping_type == 1) {
                    $('.shipping_cost_div').hide();
                    $('#shipping_cost').val(0);
                }else {
                    $('.shipping_cost_div').show();
                    $('#shipping_cost').val(0);
                }
            }
            function galleryImage(data, divId){
                if(data.files){

                    $.each( data.files, function(key,value) {
                        $('#gallery_img_prev').empty();
                        var reader = new FileReader();
                        reader.onload = function (e) {
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
            function VariantImageChange(data,sku,id){

                let formData = new FormData();
                let photo = $(id)[0].files[0];
                if (photo) {
                    formData.append('variant_image', photo)
                }
            }

            //related product
            function relatedProductAll(el){
                if(el.checked){
                    $("input[name*='related_product']").prop('checked',true);
                }else{
                    $("input[name*='related_product']").prop('checked',false);
                }
            }

            //up sale
            function upSaleAll(el){
                if(el.checked){
                    $("input[name*='up_sale']").prop('checked',true);
                }else{
                    $("input[name*='up_sale']").prop('checked',false);
                }
            }

            //cross sale
            function crossSaleAll(el){
                if(el.checked){
                    $("input[name*='cross_sale']").prop('checked',true);
                }else{
                    $("input[name*='cross_sale']").prop('checked',false);
                }
            }
             // tag
         // when page load get tag before focus
         var ENDPOINT = "{{ url('/') }}";
        
        @if(isModuleActive('FrontendMultiLang'))
        var url = ENDPOINT + '/setup/getTagBySentence';
         var sentence = $("#product_name_{{auth()->user()->lang_code}}").val();
        $.get(url,{sentence:sentence},function(result){
            $("#tag_show").append(result);
        })
        $(document).on('click', '.tag-add', function(e){
            e.preventDefault();
            $('#tag-input-upload-shots').tagsinput('add', $(this).text());
        });
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
        var url = ENDPOINT + '/setup/getTagBySentence';
         var sentence = $("#product_name").val();
        $.get(url,{sentence:sentence},function(result){
            $("#tag_show").append(result);
        })
        $(document).on('click', '.tag-add', function(e){
            e.preventDefault();
            $('#tag-input-upload-shots').tagsinput('add', $(this).text());
        });
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
                                    <div class="items_min_icon "><i class="ti-trash"></i></div>
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
                                    <button type="button" class="pl-0 pb-0 pr-0 remove_variant_whole_sale border-0">
                                        <div class="items_min_icon "><i class="ti-trash"></i></div>
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

                                                            <td class="pl-0 pb-0 pr-0 border-0">
                                                                <div class="add_items_button pt-10">
                                                                    <button type="button" class="primary-btn radius_30px add_single_whole_sale_price  fix-gr-bg">
                                                                        <i class="ti-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>`);
                    }
                }else{
                    $('.whole_sale_info_add').css('display', 'none');
                    $('.whole_sale_price_list').remove();
                    $('.whole_sale_price_list_child').remove();
                }
            });
    $(document).on('change','#relatedProductAll', function(event){
        relatedProductAll($(this)[0]);
    });

    $(document).on('change','#upSaleAll', function(event){
        upSaleAll($(this)[0]);
    });

    $(document).on('change','#crossSaleAll', function(event){
        crossSaleAll($(this)[0]);
    });    
    //related product
        let RelatedProduct = new Object();
        RelatedProduct.data = [];
        $("input[name*='related_product']:checked").each(function () {
            RelatedProduct.data.push(this.value);
        });
        $('#related_product_hidden_id').val(JSON.stringify(RelatedProduct.data));
        function relatedProductAll(el){
            if(el.checked){
                $("input[name*='related_product']").prop('checked',true);
                $("input[name*='related_product']:checked").each(function () {
                    RelatedProduct.data.push(this.value);
                });
                $('#related_product_hidden_id').val(JSON.stringify(RelatedProduct.data));
            }else{
                $("input[name*='related_product']").prop('checked',false);
                $("input[name*='related_product']").each(function (value) {
                    var id =this.value;
                    RelatedProduct.data = jQuery.grep(RelatedProduct.data, function(value) {
                        return value != parseInt(id);
                    });
                });
                $('#related_product_hidden_id').val(JSON.stringify(RelatedProduct.data));
            }
        }
        //up sale
        let UpsaleProduct = new Object();
            UpsaleProduct.data = [];
            $("input[name*='up_sale']:checked").each(function () {
                UpsaleProduct.data.push(this.value);
            });
            $('#upsale_product_hidden_id').val(JSON.stringify(UpsaleProduct.data));
        function upSaleAll(el){
            if(el.checked){
                $("input[name*='up_sale']").prop('checked',true);
                $("input[name*='up_sale']:checked").each(function () {
                    UpsaleProduct.data.push(this.value);
                });
                $('#upsale_product_hidden_id').val(JSON.stringify(UpsaleProduct.data));
            }else{
                $("input[name*='up_sale']").prop('checked',false);
                $("input[name*='up_sale']").each(function (value) {
                    var id =this.value;
                    UpsaleProduct.data = jQuery.grep(UpsaleProduct.data, function(value) {
                        return value != parseInt(id);
                    });
                });
                $('#upsale_product_hidden_id').val(JSON.stringify(UpsaleProduct.data));
                
            }
        }
        //cross sale
        let CrosssaleProduct = new Object();
            CrosssaleProduct.data = [];
            $("input[name*='cross_sale']:checked").each(function () {
                CrosssaleProduct.data.push(this.value);
            });
            $('#crosssale_product_hidden_id').val(JSON.stringify(CrosssaleProduct.data));
        function crossSaleAll(el){
            if(el.checked){
                $("input[name*='cross_sale']").prop('checked',true);
                $("input[name*='cross_sale']:checked").each(function () {
                    CrosssaleProduct.data.push(this.value);
                });
                $('#crosssale_product_hidden_id').val(JSON.stringify(CrosssaleProduct.data));
            }else{
                $("input[name*='cross_sale']").prop('checked',false);
                $("input[name*='cross_sale']").each(function (value) {
                    var id =this.value;
                    CrosssaleProduct.data = jQuery.grep(CrosssaleProduct.data, function(value) {
                        return value != parseInt(id);
                    });
                });
                $('#crosssale_product_hidden_id').val(JSON.stringify(CrosssaleProduct.data));
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
                var id = $('#product_id').val();
                $.ajax({
                    url: "/products/related-products-pagination?page=" + page,
                    type: "GET",
                    data: {
                        "search": search,
                        "id": id,
                        "spage": 'edit',
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
                var id = $('#product_id').val();
                $.ajax({
                    url: "{{route('product.related.product')}}",
                    type: "GET",
                    data: {
                        "search": search,
                        "ids": $('#related_product_hidden_id').val(),
                        "type": 'empty',
                        "id": id,
                        "spage": 'edit',
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
                var id = $('#product_id').val();
                $.ajax({
                    url: "/products/upsale-products-pagination?page=" + page,
                    type: "GET",
                    data: {
                        "search": search,
                        "id": id,
                        "spage": 'edit',
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
                var id = $('#product_id').val();
                $.ajax({
                    url: "{{route('product.upsale.product')}}",
                    type: "GET",
                    data: {
                        "search": search,
                        "id": id,
                        "ids": $('#upsale_product_hidden_id').val(),
                        "type": 'empty',
                        "spage": 'edit',
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
                var id = $('#product_id').val();
                $.ajax({
                    url: "/products/crosssale-products-pagination?page=" + page,
                    type: "GET",
                    data: {
                        "search": search,
                        "id": id,
                        "spage": 'edit',
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
                var id = $('#product_id').val();
                $.ajax({
                    url: "{{route('product.crosssale.product')}}",
                    type: "GET",
                    data: {
                        "search": search,
                        "id": id,
                        "ids": $('#crosssale_product_hidden_id').val(),
                        "type": 'empty',
                        "spage": 'edit',
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
