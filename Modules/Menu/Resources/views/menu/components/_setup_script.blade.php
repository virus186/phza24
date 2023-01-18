@push('scripts')

    <script src="{{asset(asset_path('backend/vendors/js/nestable2.js'))}}"></script>
    <script>
        (function($){
            $(document).ready(function(){

                // $(document).on('mouseover','body', function(){

                    

                // });

                $(document).on('submit', '#columnEditForm', function(event){
                    event.preventDefault();
                    let column = $('#edit_column').val();
                    let size = $('#edit_size').val();
                    if(column != "" && size != null){
                        $('#pre-loader').removeClass('d-none');
                        var formElement = $(this).serializeArray()
                        var formData = new FormData();
                        formElement.forEach(element => {
                            formData.append(element.name, element.value);
                        });

                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('menu_id','{{$menu->id}}');
                        $('#edit_column_modal').modal('hide');

                        $.ajax({
                            url: "{{ route('menu.setup.column-update') }}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function(response) {
                                $('#pre-loader').addClass('d-none');
                                reloadWithData(response);
                                toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                            },
                            error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }


                            }
                        });

                    }else{
                        if(column ==''){
                            $('#error_edit_column').text('Column name required.')
                        }if(size == null){
                            $('#error_edit_size').text('Column size required.')
                        }
                    }
                });
                

                $(document).on('submit', '#elementEditForm', function(event){
                    event.preventDefault();

                    let element_type = $(this).data('element_type');
                    if(element_type == 'category' && !$(this).find('.edit_category').val()){
                        toastr.error('Please Sellect Category');
                        return false;
                    }

                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });

                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('menu_id','{{$menu->id}}');
                    $('#edit_element_modal').modal('hide');

                    $.ajax({
                        url: "{{ route('menu.setup.element-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            reloadWithData(response);
                            $('#pre-loader').addClass('d-none');
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");

                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });

                });

                $(document).on('submit', '#menuEditForm', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });

                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('menu_id','{{$menu->id}}');
                    $('#edit_element_modal').modal('hide');

                    $.ajax({
                        url: "{{ route('menu.setup.menu-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            reloadWithData(response);
                            $('#pre-loader').addClass('d-none');
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");

                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });

                });

                $(document).on('submit', '#rightPanelDataEditForm', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });

                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('menu_id','{{$menu->id}}');

                    $.ajax({
                        url: "{{ route('menu.setup.rightpanel-data-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {

                            $('#rightpanelListDiv').empty();
                            $('#rightpanelListDiv').html(response);
                            dynamicSelect2WithAjax(".right_category", "{{url('/products/get-category-data')}}", "GET");
                            $('#pre-loader').addClass('d-none');
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");

                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });

                });

                $(document).on('submit', '#bottomPanelDataEditForm', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });

                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('menu_id','{{$menu->id}}');

                    $.ajax({
                        url: "{{ route('menu.setup.bottompanel-data-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {

                            $('#bottompanelListDiv').empty();
                            $('#bottompanelListDiv').html(response);
                            dynamicSelect2WithAjax(".bottom_brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                            $('#pre-loader').addClass('d-none');
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");

                        },
                        error: function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                            toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                        }
                    });

                });



                //for delete functionality
                $(document).on('submit', '#column_delete_form', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#deleteColumnModal').modal('hide');
                    let id = $('#delete_column_id').val();
                    let data = {
                        'id' : id,
                        '_token' : '{{ csrf_token() }}',
                        'menu_id':'{{$menu->id}}'
                    }
                    $.post("{{ route('menu.setup.column-delete') }}",data, function(data){
                        $('#pre-loader').addClass('d-none');
                        toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                        reloadWithData(data);

                    })
                    .fail(function(response) {
                    if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }

                    });
                });
                $(document).on('submit', '#element_delete_form', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#deleteElementModal').modal('hide');
                    let id = $('#delete_element_id').val();
                    let data = {
                        'id' : id,
                        '_token' : '{{ csrf_token() }}',
                        'menu_id':'{{$menu->id}}'
                    }
                    $.post("{{ route('menu.setup.element-delete') }}",data, function(data){
                        $('#pre-loader').addClass('d-none');
                        toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                        reloadWithData(data);

                    })
                    .fail(function(response) {
                    if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }

                    });
                });

                $(document).on('submit', '#menu_delete_form', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#deleteMenuModal').modal('hide');
                    let id = $('#delete_menu_id').val();
                    let data = {
                        'id' : id,
                        '_token' : '{{ csrf_token() }}',
                        'menu_id':'{{$menu->id}}'
                    }
                    $.post("{{ route('menu.setup.menu-delete') }}",data, function(data){
                        $('#pre-loader').addClass('d-none');
                        toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                        reloadWithData(data);

                    })
                    .fail(function(response) {
                    if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }

                    });
                });

                $(document).on('submit', '#category_delete_form', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#deleteCategoryModal').modal('hide');
                    let id = $('#delete_category_id').val();
                    let data = {
                        'id' : id,
                        '_token' : '{{ csrf_token() }}',
                        'menu_id':'{{$menu->id}}'
                    }
                    $.post("{{ route('menu.setup.category-delete') }}",data, function(data){

                        $('#rightpanelListDiv').empty();
                        $('#rightpanelListDiv').html(data);
                        dynamicSelect2WithAjax(".right_category", "{{url('/products/get-category-data')}}", "GET");
                        $('#pre-loader').addClass('d-none');
                        toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                    })
                    .fail(function(response) {
                    if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }

                    });
                });

                $(document).on('submit', '#brand_delete_form', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    $('#deleteBrandModal').modal('hide');
                    let id = $('#delete_brand_id').val();
                    let data = {
                        'id' : id,
                        '_token' : '{{ csrf_token() }}',
                        'menu_id':'{{$menu->id}}'
                    }
                    $.post("{{ route('menu.setup.brand-delete') }}",data, function(data){

                        $('#bottompanelListDiv').empty();
                        $('#bottompanelListDiv').html(data);
                        dynamicSelect2WithAjax(".bottom_brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                        $('#pre-loader').addClass('d-none');
                        toastr.success("{{__('common.deleted_successfully')}}", "{{__('common.success')}}");
                    })
                    .fail(function(response) {
                    if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }

                    });
                });

                // $(document).on('mouseover','body',function(){
                initSortable();
                function initSortable(){    

                    $('.dd').nestable({
                        maxDepth:5,
                        callback:function(l,e){
                            let order = JSON.stringify($('.dd').nestable('serialize'));
                            let data = {
                                'order' : order,
                                '_token' : '{{ csrf_token() }}',
                                'menu_id' : '{{$menu->id}}'
                            }
                            $.post('{{route('menu.setup.normal-menu.order')}}',data, function(data){
                                if(data != 1){
                                    toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                                }
                            })
                            .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }
                            });
                        }
                    });

                    $('#itemDiv').sortable({
                        cursor: "move",
                        containment: "parent",
                        update:function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            if(ids.length > 0){
                                $.post("{{ route('menu.setup.sort-column') }}",{'_token':'{{ csrf_token() }}','ids' : ids}, function(data){

                                })
                                .fail(function(response) {
                                if(response.responseJSON.error){
                                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                        $('#pre-loader').addClass('d-none');
                                        return false;
                                    }

                                });
                            }

                        }

                    }).disableSelection();

                    $(".item_list").sortable({
                        cursor: "move",
                        connectWith: ["#elementDiv",".item_list"],
                        update:function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            if(ids.length > 0){
                                $.post("{{ route('menu.setup.sort-element') }}",{'_token':'{{ csrf_token() }}','ids' : ids}, function(data){

                                })
                                .fail(function(response) {
                                if(response.responseJSON.error){
                                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                        $('#pre-loader').addClass('d-none');
                                        return false;
                                    }

                                });
                            }

                        },
                        receive: function(event, ui){
                            let column_id = event.target.attributes[1].value;
                            let element = ui.item[0].attributes[1].value;
                            let data ={
                                'column_id' : column_id,
                                'element' : element,
                                '_token' : '{{ csrf_token() }}'
                            }

                            $.post("{{ route('menu.setup.add-to-column') }}",data, function(data){

                            })

                            .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });
                        }
                    }).disableSelection();

                    $('#elementDiv').sortable({
                        connectWith: ".item_list",
                        cursor: "move",
                        update:function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            if(ids.length > 0){
                                $.post("{{ route('menu.setup.sort-element') }}",{'_token':'{{ csrf_token() }}','ids' : ids}, function(data){

                                })
                                .fail(function(response) {
                                if(response.responseJSON.error){
                                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                        $('#pre-loader').addClass('d-none');
                                        return false;
                                    }

                                });
                            }

                        },
                        receive: function(event, ui){
                            let element = ui.item[0].attributes[1].value;
                            let data ={
                                'element' : element,
                                '_token' : '{{ csrf_token() }}'
                            }
                            $.post("{{ route('menu.setup.remove-from-column') }}",data, function(data){

                            })
                            .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });

                        }
                    }).disableSelection();

                    $('#menuDiv').sortable({
                        cursor:"move",
                        update: function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            if(ids.length > 0){
                                let data = {
                                    '_token' :'{{ csrf_token() }}',
                                    'ids' : ids,
                                    'menu_id' : '{{$menu->id}}'
                                }
                                $.post("{{ route('menu.setup.sort-menu') }}", data, function(data){

                                })
                                .fail(function(response) {
                                if(response.responseJSON.error){
                                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                        $('#pre-loader').addClass('d-none');
                                        return false;
                                    }

                                });
                            }
                        }
                    }).disableSelection();

                    $('#rightpanelListDiv').sortable({
                        cursor:"move",
                        update: function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            if(ids.length > 0){
                                let data = {
                                    '_token' :'{{ csrf_token() }}',
                                    'ids' : ids,
                                    'menu_id' : '{{$menu->id}}'
                                }
                                $.post("{{ route('menu.setup.category-sort') }}", data, function(data){

                                })

                                .fail(function(response) {
                                if(response.responseJSON.error){
                                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                        $('#pre-loader').addClass('d-none');
                                        return false;
                                    }

                                });
                            }
                        }
                    }).disableSelection();

                    $('#bottompanelListDiv').sortable({
                        cursor:"move",
                        update: function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            if(ids.length > 0){
                                let data = {
                                    '_token' :'{{ csrf_token() }}',
                                    'ids' : ids,
                                    'menu_id' : '{{$menu->id}}'
                                }
                                $.post("{{ route('menu.setup.brand-sort') }}", data, function(data){

                                })
                                .fail(function(response) {
                                if(response.responseJSON.error){
                                        toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                        $('#pre-loader').addClass('d-none');
                                        return false;
                                    }

                                });
                            }
                        }
                    }).disableSelection();
                // });
                }
                



                $(document).on('click','#add_row_btn', function(event){
                    let row = $('#row').val();
                    let size = $('#size').val();
                    let id = '{{$menu->id}}';
                    if(row != "" && size != ""){

                        $('#pre-loader').removeClass('d-none');

                        $.post("{{route('menu.setup.add-column')}}",{'column' : row,'_token' : '{{ csrf_token() }}','size' : size,'id' :id}, function(data){
                            if(data){
                                if(data.limit_cross){
                                    toastr.warning(data.limit_cross,'Warning');
                                }else{
                                    toastr.success("{{__('common.created_successfully')}}", "{{__('common.success')}}");
                                    reloadWithData(data);
                                }
                                $('#row').val('');
                                $('#size').val('');
                                $('#size').niceSelect('update');

                            }else{
                                toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");

                            }
                            $('#pre-loader').addClass('d-none');
                        })

                        .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });
                    }else{
                        if(row == ''){
                            toastr.error("{{__('menu.row_required')}}","{{__('common.error')}}");
                        }
                        if(size == ""){
                            toastr.error("{{__('menu.size_required')}}","{{__('common.error')}}");
                        }
                    }
                });

                $(document).on('click', '#add_category_btn', function(event){
                    let category = $('#category').val();
                    let catText = $('#category option:selected').text();
                    if(category.length > 0){

                        $('#category').val('');
                        dynamicSelect2WithAjax("#category", "{{url('/products/get-category-data')}}", "GET");
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'category',
                            'element_id' : category,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })
                        .fail(function(response) {
                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        });

                    }else{
                        toastr.error("{{__('menu.category_required')}}","{{__('common.error')}}");
                    }
                });
                $(document).on('click', '#add_link_btn', function(event){
                    let link = $('#link').val();
                    let title = $('#title').val();
                    if(title != ""){

                        $('#link').val('');
                        $('#title').val('');
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'link',
                            'link' : link,
                            'title' : title,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');

                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })
                        .fail(function(response) {
                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        });
                    }else{
                        if(title == ''){
                            toastr.error("{{__('menu.title_required')}}","{{__('common.error')}}");
                        }
                    }
                });

                $(document).on('click', '#add_page_btn', function(event){
                    let page = $('#page').val();
                    let pageText = $('#page option:selected').text();
                    if(page.length > 0){

                        $('#page').val('');
                        $('#page').niceSelect('update');
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'page',
                            'element_id' : page,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })

                        .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });
                    }else{
                        toastr.error("{{__('menu.link_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click', '#add_product_btn', function(event){
                    let product = $('#product').val();
                    let productText = $('#product option:selected').text();
                    if(product.length > 0){

                        $('#product').val('');
                        dynamicSelect2WithAjax("#product", "{{url('/products/seller-products/get-by-ajax')}}", "GET");
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'product',
                            'element_id' : product,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })

                        .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });
                    }else{
                        toastr.error("{{__('menu.link_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click', '#add_brand_btn', function(event){
                    let brand = $('#brand').val();

                    if(brand.length > 0){

                        $('#brand').val('');
                        dynamicSelect2WithAjax("#brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'brand',
                            'element_id' : brand,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })
                        .fail(function(response) {
                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        });
                    }else{
                        toastr.error("{{__('menu.brand_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click', '#add_tag_btn', function(event){
                    let tag = $('#tag').val();
                    if(tag.length >0){
                        $('#tag').val('');
                        dynamicSelect2WithAjax("#tag", "{{url('/setup/tags/get-by-ajax')}}", "GET");
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'tag',
                            'element_id' : tag,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })
                        .fail(function(response) {
                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        });
                    }else{
                        toastr.error("{{__('menu.tags_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click', '#add_func_btn', function(event){
                    let func = $('#function').val();
                    if(func){
                        $('#function').val('');
                        $('#function').niceSelect('update');
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'function',
                            'element_id' : func,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-element')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })
                        .fail(function(response) {
                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        });
                    }else{
                        toastr.error("{{__('Please select first.')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click','#add_menu_btn', function(event){
                    let menus = $('#menu').val();
                    if(menus.length >0){
                        $('#menu').val('');
                        $('#menu').niceSelect('update');
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'type' : 'tag',
                            'menus' : menus,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-menu')}}",data, function(data){
                            if(data){
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");
                                reloadWithData(data);

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })

                        .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });
                    }else{
                        toastr.error("{{__('menu.menu_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click','#add_category_rightpanel_btn', function(event){
                    let categories = $('#category_rightpanel').val();
                    if(categories.length >0){
                        $('#category_rightpanel').val('');
                        dynamicSelect2WithAjax("#category_rightpanel", "{{url('/products/get-category-data')}}", "GET");
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'categories' : categories,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-rightpanel-data')}}",data, function(data){
                            if(data){
                                $('#rightpanelListDiv').empty();
                                $('#rightpanelListDiv').html(data);
                                dynamicSelect2WithAjax(".right_category", "{{url('/products/get-category-data')}}", "GET");
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })

                        .fail(function(response) {
                            if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }

                            });
                    }else{
                        toastr.error("{{__('menu.category_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click','#add_brand_bottompanel_create_btn', function(event){
                    let brands = $('#brand_bottompanel').val();
                    if(brands.length >0){
                        $('#brand_bottompanel').val('');
                        dynamicSelect2WithAjax("#brand_bottompanel", "{{route('product.brands.get-by-ajax')}}", "GET");
                        let data = {
                            'menu_id' : '{{$menu->id}}',
                            'brands' : brands,
                            '_token' : '{{ csrf_token() }}'
                        }
                        $('#pre-loader').removeClass('d-none');
                        $.post("{{route('menu.setup.add-bottompanel-data')}}",data, function(data){
                            if(data){
                                $('#bottompanelListDiv').empty();
                                $('#bottompanelListDiv').html(data);
                                dynamicSelect2WithAjax(".bottom_brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                                toastr.success("{{__('common.added_successfully')}}", "{{__('common.success')}}");

                            }else{
                                toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                            }
                            $('#pre-loader').addClass('d-none');
                        })
                        .fail(function(response) {
                        if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }

                        });
                    }else{
                        toastr.error("{{__('menu.brand_required')}}","{{__('common.error')}}");
                    }
                });

                $(document).on('click', '.column_delete_btn', function(event) {
                    event.preventDefault();
                    let id = $(this).data('id');
                    if(id != null){
                        $('#delete_column_id').val(id);
                        $('#deleteColumnModal').modal('show');

                    }else{
                        toastr.error("{{ __('common.error_message') }}","{{__('common.error')}}")
                    }

                });

                $(document).on('click', '.element_delete_btn', function(event) {
                    event.preventDefault();
                    let id = $(this).data('id');
                    if(id != null){
                        $('#delete_element_id').val(id);
                        $('#deleteElementModal').modal('show');

                    }else{
                        toastr.error("{{ __('common.error_message') }}","{{__('common.error')}}");
                    }

                });
                $(document).on('click', '.menu_delete_btn', function(event) {
                    event.preventDefault();
                    let id = $(this).data('id');
                    if(id != null){
                        $('#delete_menu_id').val(id);
                        $('#deleteMenuModal').modal('show');

                    }else{
                        toastr.error("{{__('common.error_message')}}", "{{__('common.error')}}");
                    }

                });

                $(document).on('click', '.right_panel_category_delete_btn', function(event) {
                    event.preventDefault();
                    let id = $(this).data('id');

                    if(id != null){
                        $('#delete_category_id').val(id);
                        $('#deleteCategoryModal').modal('show');

                    }else{
                        toastr.error("{{ __('common.error_message') }}","{{__('common.error')}}");
                    }

                });

                $(document).on('click', '.bottom_panel_brand_delete_btn', function(event) {
                    event.preventDefault();
                    let id = $(this).data('id');

                    if(id != null){
                        $('#delete_brand_id').val(id);
                        $('#deleteBrandModal').modal('show');

                    }else{
                        toastr.error("{{ __('common.error_message') }}","{{__('common.error')}}");
                    }

                });

                $(document).on('submit', '#ads_form', function(event){
                    event.preventDefault();
                    $('#widget_form_btn').prop('disabled',true);
                    $('#widget_form_btn').text('{{ __("common.updating") }}');
                    $('#pre-loader').removeClass('d-none');

                    $('#error_title').text('');
                    $('#error_subtitle').text('');
                    $('#error_link').text('');
                    $('#error_image').text('');

                    let formElement = $(this).serializeArray()
                    let formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });
                    let status = 0;
                    if ($('#status').is(":checked")){
                        status =1;
                    }else{
                        status = 0;
                    }
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('status', status);
                    if($('#image').length){
                        let file_length = document.getElementById('image').files.length;
                        if(file_length == 1){
                            formData.append('image', document.getElementById('image').files[0]);
                        }
                    }
                    $.ajax({
                        url: "{{ route('menu.setup.ads-update') }}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            $('#pre-loader').addClass('d-none');
                            $('#ads_form_btn').prop('disabled',false);
                            $('#ads_form_btn').text('{{ __("common.update") }}');
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                        },
                        error: function(response) {
                            $('#pre-loader').addClass('d-none');
                            $('#ads_form_btn').prop('disabled',false);
                            $('#ads_form_btn').text('{{ __("common.update") }}');

                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                            }else{

                                $('#error_title').text(response.responseJSON.errors.title);
                                $('#error_subtitle').text(response.responseJSON.errors.subtitle);
                                $('#error_link').text(response.responseJSON.errors.link);
                                $('#error_image').text(response.responseJSON.errors.image);
                            }

                        }
                    });
                });

                $(document).on('change', '.image_file', function(event){
                    let name_id = $(this).data('name_id');
                    let view_id = $(this).data('view_id');
                    getFileName($(this).val(),name_id);
                    imageChangeWithFile($(this)[0], view_id);
                });

                function reloadWithData(response){
                    $('#div333').empty();
                    $('#div333').append(response);
                    $('.edit_page').niceSelect();
                    dynamicSelect2WithAjax(".edit_category", "{{url('/products/get-category-data')}}", "GET");
                    dynamicSelect2WithAjax(".edit_product", "{{url('/products/seller-products/get-by-ajax')}}", "GET");
                    dynamicSelect2WithAjax(".edit_brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                    dynamicSelect2WithAjax(".edit_tag", "{{url('/setup/tags/get-by-ajax')}}", "GET");
                    $('.edit_function').niceSelect();
                    $('.edit_size').niceSelect();
                    $('.edit_menu').niceSelect();
                    initSortable();
                }
                dynamicSelect2WithAjax("#category", "{{url('/products/get-category-data')}}", "GET");
                dynamicSelect2WithAjax(".edit_category", "{{url('/products/get-category-data')}}", "GET");
                dynamicSelect2WithAjax("#category_rightpanel", "{{url('/products/get-category-data')}}", "GET");
                dynamicSelect2WithAjax(".right_category", "{{url('/products/get-category-data')}}", "GET");

                dynamicSelect2WithAjax("#product", "{{url('/products/seller-products/get-by-ajax')}}", "GET");
                dynamicSelect2WithAjax(".edit_product", "{{url('/products/seller-products/get-by-ajax')}}", "GET");

                dynamicSelect2WithAjax("#brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                dynamicSelect2WithAjax(".edit_brand", "{{route('product.brands.get-by-ajax')}}", "GET");
                dynamicSelect2WithAjax("#brand_bottompanel", "{{route('product.brands.get-by-ajax')}}", "GET");
                dynamicSelect2WithAjax(".bottom_brand", "{{route('product.brands.get-by-ajax')}}", "GET");

                dynamicSelect2WithAjax("#tag", "{{url('/setup/tags/get-by-ajax')}}", "GET");
                dynamicSelect2WithAjax(".edit_tag", "{{url('/setup/tags/get-by-ajax')}}", "GET");
                
            });
        })(jQuery);

    </script>
@endpush
