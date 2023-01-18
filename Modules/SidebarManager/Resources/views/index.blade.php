@extends('backEnd.master')
@section('styles')  
    <link rel="stylesheet" href="{{asset('Modules/SidebarManager/Resources/assets/css/style.css')}}" />
@endsection
@section('mainContent')

    <div class="role_permission_wrap">
        <div class="permission_title d-flex flex-wrap justify-content-between mb_20">
            <h4>{{ trans('common.sidebar_manager') }} {{ trans('common.for') }} ({{auth()->user()->first_name}})</h4>
            <a href="#" id="resetMenu" class="primary-btn radius_30px mr-10 fix-gr-bg">{{__('general_settings.Reset to default')}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb_20">
            <div class="white-box available_box">
                <div class="add-visitor">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0 create-title" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="false"
                                aria-controls="collapseOne">
                                    <button class="btn btn-link add_btn_link">
                                        {{__('Add section')}}
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <form action="" id="addSectionForm">
                                        <div id="row_element_div" class="row">
                                            <div class="col-lg-12">
                                                <div class="primary_input mb-25">
                                                    <label class="primary_input_label" for="name">{{ __('common.name') }} <span class="textdanger">*</span>
                                                    </label>
                                                    <input class="primary_input_field name" type="text" id="section_name"
                                                        name="name" autocomplete="off" placeholder="{{__('common.name')}}">
                                                </div>
                                                <span class="text-danger" id="error_section_name"></span>
                                            </div>
                                            <div class="col-lg-12 text-center">
                                                <button id="add_row_btn" type="submit"
                                                    class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip"
                                                    title="" data-original-title="">
                                                    <span class="ti-check"></span>
                                                    {{__('common.save')}} </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt_20" id="available_menu_div">
                        @include('sidebarmanager::components.available_list')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb_20">
            <div class="white-box">
                <input type="hidden" name="data" id="items-data" value="">
                <div class="add-visitor" id="menu_idv">
                    @include('sidebarmanager::components.components')
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="white-box">
                <div class="add-visitor" id="live_preview_div">
                    @include('sidebarmanager::components.live_preview')
                </div>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function(){
                $("#previewMenu").metisMenu();
                initSortable();
                
                function initSortable(){
                    $('#itemDiv').sortable({
                        cursor: "move",
                        containment: "parent",
                        update:function(event, ui){
                            let ids = $(this).sortable('toArray',{ attribute: 'data-id'});
                            let data = {
                                ids: ids,
                                _token : '{{ csrf_token() }}'
                            };
                            $.post("{{ route('sidebar-manager.sort-section') }}",data, function(response){
                                $('#live_preview_div').html(response.live_preview);
                                $("#previewMenu").metisMenu();
                                $('#pre-loader').addClass('d-none');
                            }).fail(function(response) {
                                if(response.responseJSON.error){
                                    toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                    $('#pre-loader').addClass('d-none');
                                    return false;
                                }
                            });

                        }
                    }).disableSelection();

                    $('.dd-list').sortable({
                        cursor: "move",
                        connectWith: [".dd-list"],
                        // scroll: false,
                        helper: 'clone',
                        appendTo: 'body',
                        update:function(event, ui){
                            
                            makeFirstChildRoot();
                            saveItemsPosition();
                            checkEmptyList();
                            
                        },
                        receive: function(event, ui){
                            let parent_id = event.target.attributes[1].value;
                            let section_id = event.target.attributes[2].value;

                            ui.item.attr("data-section_id", parent_id);
                            ui.item.attr("data-parent_id", parent_id);
                            ui.item.removeClass('ml_20')
                            

                            makeFirstChildRoot();
                            saveItemsPosition();
                            checkEmptyList();
                        }
                    });
                }

                function checkEmptyList(){
                    $('.dd-list').each(function(){
                        if ($(this).has("div").length < 1) {
                            $(this).html(`
                                <span class="empty_list">No more items available</span>
                            `);
                        }else{
                            $(this).find('.empty_list').remove();
                        }
                    })
                }

                function makeFirstChildRoot(){
                    $(".menu-list div:first-child").removeClass("ml_20");
                }

                function reloadAfterChange(){
                    
                }

                //for update menu list
                function saveItemsPosition() {
                    var items = [];

                    $(".menu_item_div .listed_menu").each(function () {
                        var id = $(this).attr("data-id");
                        var section_id = $(this).attr("data-section_id");
                        var parent_id = $(this).attr("data-parent_id");
                        if(parent_id == undefined){
                            parent_id = $(this).prev().attr('data-section_id');
                        }
                        if(section_id == undefined){
                            section_id = $(this).prev().attr('data-section_id');
                        }

                        if (id) {
                            var itemObject = {id: id, parent_id:parent_id ,section_id: section_id};

                            //sub menu
                            if ($(this).hasClass("ml_20")) {
                                itemObject["is_sub_menu"] = "1";
                            }

                            items.push(itemObject);
                        }
                    });

                    //convert array to json data and save into an input field
                    if (Object.keys(items).length) {
                        $("#items-data").val(JSON.stringify(items));
                        let ids = JSON.stringify(items);

                        $.post("{{route('sidebar-manager.menu-update')}}",{'_token':'{{ csrf_token() }}','ids' : ids}, function(response){
                            $('#live_preview_div').html(response.live_preview);
                            $("#previewMenu").metisMenu();
                        })
                        .fail(function(response) {
                            if(response.responseJSON.error){
                                toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                                $('#pre-loader').addClass('d-none');
                                return false;
                            }
                        });

                    } else {
                        $("#items-data").val("");
                    }
                }

                $(document).on('click', '.make-sub-menu', function () {
                    // console.log('hello2')
                    var $item = $(this).closest(".listed_menu");
                    var $clickableIcon = $(this).closest(".toggle-menu-icon");
                    $item.addClass("ml_20");
                    $clickableIcon.addClass("make-root-menu");
                    $clickableIcon.removeClass("make-sub-menu");
                    $clickableIcon.html("<i class='ti-back-right'></i>");
                    saveItemsPosition();
                    // feather.replace();
                });

                //make root menu
                $(document).on('click', '.make-root-menu', function () {
                    // console.log('hello1')
                    var $item = $(this).closest(".listed_menu");
                    var parent_id = $item.prev().attr('data-section_id');
                    $item.attr('data-parent_id', parent_id);
                    var $clickableIcon = $(this).closest(".toggle-menu-icon");
                    $item.removeClass("ml_20");
                    $clickableIcon.removeClass("make-root-menu");
                    $clickableIcon.addClass("make-sub-menu");
                    $clickableIcon.html("<i class='ti-back-left'></i>");

                    saveItemsPosition();
                    // feather.replace();
                });

                $(document).on('click', '.remove_menu', function () {
                    //restore the selected item to item container
                    var $item = $(this).closest(".listed_menu"),
                    itemClone = $item.clone();

                    //don't restore custom menu item
                    itemClone.removeClass("ml_20");
                    $("#available_list").append(itemClone);

                    //remove drag/drop text from item container
                    removeEmptyAreaText($("#available_list"));

                    //remove the row finally
                    $item.fadeOut(300, function () {
                        $item.remove();

                        saveItemsPosition();
                        checkEmptyList();
                        // addEmptyAreaText($(".sortable-items-container"));
                    });
                    // adjustHeightOfItemsContainer();
                });

                //remove drag/drop text from new added area if there is no elements available
                function removeEmptyAreaText(index) {
                    if ($(index).has("div").length > 0) {
                        $(index).find("span.empty-area-text").remove();
                    }
                }

                

                $(document).on('click', '.toggle_up_down', function(event){
                    $(this).parent().parent().siblings(".menu_list").toggleClass('d-none');
                    if($(this).hasClass('ti-angle-up')){
                        $(this).removeClass('ti-angle-up');
                        $(this).addClass('ti-angle-down');
                    }
                    else if($(this).hasClass('ti-angle-down')){
                        $(this).removeClass('ti-angle-down');
                        $(this).addClass('ti-angle-up');
                    }
                });

                $(document).on('submit', '#addSectionForm', function(event){
                    event.preventDefault();
                    $('#pre-loader').removeClass('d-none');
                    var formElement = $(this).serializeArray()
                    var formData = new FormData();
                    formElement.forEach(element => {
                        formData.append(element.name, element.value);
                    });

                    formData.append('_token', "{{ csrf_token() }}");

                    $.ajax({
                        url: "{{route('sidebar-manager.section.store')}}",
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(response) {
                            $('#menu_idv').html(response.menus);
                            $('#available_menu_div').html(response.available_list);
                            $('#live_preview_div').html(response.live_preview);
                            $("#previewMenu").metisMenu();
                            $('#pre-loader').addClass('d-none');
                            $('#section_name').val('');
                            initSortable();
                            toastr.success("{{__('common.created_successfully')}}", "{{__('common.success')}}");

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

                $(document).on('click', '.delete_section', function(){
                    let id = $(this).data('id');
                    let data = {
                        id : id,
                        _token : "{{csrf_token()}}"
                    }
                    $('#pre-loader').removeClass('d-none');
                    $.post('{{route("sidebar-manager.delete-section")}}',data, function(response){
                        $('#menu_idv').html(response.menus);
                        $('#available_menu_div').html(response.available_list);
                        $('#live_preview_div').html(response.live_preview);
                        $("#previewMenu").metisMenu();
                        $('#pre-loader').addClass('d-none');
                        initSortable();
                    });
                });

                $(document).on('click', '#resetMenu', function(event){
                    event.preventDefault();
                    let data = {
                        _token : "{{csrf_token()}}"
                    }
                    $('#pre-loader').removeClass('d-none');
                    $.post('{{route("sidebar-manager.reset-own-menu")}}', data, function(response){
                        if(response.msg == 'success'){
                            toastr.success("{{__('common.updated_successfully')}}", "{{__('common.success')}}");
                            location.reload();
                        }
                    }).fail(function(response) {
                        if(response.responseJSON.error){
                            toastr.error(response.responseJSON.error ,"{{__('common.error')}}");
                            $('#pre-loader').addClass('d-none');
                            return false;
                        }
                    });
                });

            });
        })(jQuery);
    </script>




@endpush
