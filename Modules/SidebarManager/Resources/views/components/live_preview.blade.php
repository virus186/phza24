<h4>Live Preview</h4>
<div class="mt_30">
    
    <nav class="preview_menu_wrapper" >
        <ul id="previewMenu">
            @foreach($backendMenuUser as $preview_section)
                <li class="preview_section">
                    {{__(@$preview_section->backendMenu->name)}}
                </li>
                @if($preview_section->children->count())
                    @foreach($preview_section->children as $preview_menu)
                        @if(!$preview_menu->backendMenu->module or isModuleActive($preview_menu->backendMenu->module))

                            @if(permissionCheck($preview_menu->backendMenu->route))
                                @if(@$preview_menu->backendMenu->route == 'payment_gateway.index' && auth()->user()->role->type == 'seller' && !app('general_setting')->seller_wise_payment)
                                    @continue
                                @endif
                                <li class="">
                                    <a href="javascript:;" class="@if($preview_menu->children->count()) has-arrow @endif">
                                        <div class="nav_icon_small">
                                            <span class="{{$preview_menu->backendMenu->icon?$preview_menu->backendMenu->icon:'fas fa-users'}}"></span>
                                        </div>
                                        <div class="nav_title">
                                            <span>{{__(@$preview_menu->backendMenu->name)}}</span>
                                        </div>
                                    </a>
                                    @if($preview_menu->children->count())
                                        <ul>
                                            @foreach($preview_menu->children as $preview_submenu)
                                                @if(app('theme')->folder_path == 'amazy')
                                                    @if($preview_submenu->backendMenu->route == 'frontendcms.features.index')
                                                        @continue
                                                    @endif
                                                @elseif(app('theme')->folder_path == 'default')
                                                    @if($preview_submenu->backendMenu->route == 'frontendcms.ads_bar.index' || $preview_submenu->backendMenu->route == 'frontendcms.promotionbar.index' || $preview_submenu->backendMenu->route == 'frontendcms.login_page')
                                                        @continue
                                                    @endif
                                                @endif
                                                @if(@$preview_submenu->backendMenu->route == 'payment_gateway.index' && auth()->user()->role->type == 'seller' && !app('general_setting')->seller_wise_payment)
                                                    @continue
                                                @endif
                                                @if(!$preview_submenu->backendMenu->module or isModuleActive($preview_submenu->backendMenu->module))
                                                    @if(permissionCheck($preview_submenu->backendMenu->route))
                                                        <li><a href="javascript:;">{{__($preview_submenu->backendMenu->name)}}</a></li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
            
        </ul>
    </nav>
</div>