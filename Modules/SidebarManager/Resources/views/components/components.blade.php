<h4>Menu List</h4>
<div class="">
    <div class="row">
        {{-- @dd($sections) --}}
        <div class="col-xl-12 menu_item_div" id="itemDiv">
            @foreach($backendMenuUser as $section)
                <div class="closed_section" data-id="{{$section->backendmenu_id}}">
                    <!-- menu_setup_wrap  -->
                    <div class="section_nav">
                        <h5>{{__($section->backendMenu->name)}}</h5>
                        <div class="setting_icons">
                            <i class="ti-close delete_section" data-id="{{$section->id}}"></i>
                            <i class="ti-angle-up toggle_up_down"></i>
                        </div>
                    </div>
                    <div class="dd menu_list">
                        @if($section->children->count())
                        <div class="dd-list menu-list" data-id="{{$section->id}}" data-section_id="{{$section->backendmenu_id}}">
                            @foreach($section->children as $menu)
                                @if(!$menu->backendMenu->module or isModuleActive($menu->backendMenu->module))
                                    @if(permissionCheck($menu->backendMenu->route))
                                        @if(@$menu->backendMenu->route == 'payment_gateway.index' && auth()->user()->role->type == 'seller' && !app('general_setting')->seller_wise_payment)
                                            @continue
                                        @endif
                                        <!-- dd-item  -->
                                        <div class="dd-item listed_menu" data-id="{{$menu->id}}" data-parent_id="{{$section->id}}" data-section_id="{{$section->id}}">
                                            <div class="dd-handle">
                                                <div class="menu_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move icon-16 text-off mr5"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                                                </div> 
                                                {{__($menu->backendMenu->name)}}
                                                
                                            </div>
                                            <div class="edit_icon">
                                                <span class="make-sub-menu toggle-menu-icon">
                                                    <i class="ti-back-left"></i>
                                                </span>
                                                <i class="ti-close remove_menu"></i>
                                            </div>
                                            {{-- <ol class="dd-list" data-id="{{$menu->id}}" data-section_id="{{$section->backendmenu_id}}">
                                                
                                            </ol> --}}
                                        </div>
                                        @foreach($menu->children as $submenu)
                                            @if(app('theme')->folder_path == 'amazy')
                                                @if($submenu->backendMenu->route == 'frontendcms.features.index')
                                                    @continue
                                                @endif
                                            @elseif(app('theme')->folder_path == 'default')
                                                @if($submenu->backendMenu->route == 'frontendcms.ads_bar.index' || $submenu->backendMenu->route == 'frontendcms.promotionbar.index' || $submenu->backendMenu->route == 'frontendcms.login_page')
                                                    @continue
                                                @endif
                                            @endif
                                            @if(@$submenu->backendMenu->route == 'payment_gateway.index' && auth()->user()->role->type == 'seller' && !app('general_setting')->seller_wise_payment)
                                                @continue
                                            @endif
                                            @if(!$submenu->backendMenu->module or isModuleActive($submenu->backendMenu->module))
                                                @if(permissionCheck($submenu->backendMenu->route))
                                                <div class="dd-item listed_menu ml_20" data-id="{{$submenu->id}}" data-parent_id="{{$menu->id}}" data-section_id="{{$section->id}}">
                                                    <div class="dd-handle">
                                                        <div class="menu_icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move icon-16 text-off mr5"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                                                        </div> 
                                                        {{__($submenu->backendMenu->name)}}
                                                    </div>
                                                    <div class="edit_icon">
                                                        <span class="make-root-menu toggle-menu-icon">
                                                            <i class="ti-back-right"></i>
                                                        </span>
                                                        <i class="ti-close remove_menu"></i>
                                                    </div>
                                                    
                                                </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif    
                                @endif    
                            @endforeach
                            
                        </div>
                        @else
                        <div class="dd-list menu-list" data-id="{{$section->id}}" data-section_id="{{$section->backendmenu_id}}">
                            <span class="empty_list">No more items available</span>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <!--/ menu_setup_wrap  -->
        </div>
    </div>
</div>