<!-- sidebar part here -->

<nav id="sidebar" class="sidebar">
    <div class="sidebar-header update_sidebar">
        <a class="large_logo" href="{{ auth()->user()->role->type == 'seller' ? route('seller.dashboard') : route('admin.dashboard') }}">
            <img src="{{showImage(app('general_setting')->logo)}}" alt="">
        </a>
        <a class="mini_logo" href="{{ auth()->user()->role->type == 'seller' ? route('seller.dashboard') : route('admin.dashboard') }}">
            <img src="{{showImage(app('general_setting')->favicon)}}" alt="">
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>
    </div>
    @php
        \Modules\SidebarManager\Traits\SidebarTrait::latestSidebar();
        $sidebars = \Modules\SidebarManager\Entities\BackendmenuUser::with('children', 'backendMenu')->whereNull('parent_id')->where('user_id', auth()->id())->orderBy('position')->get();
    @endphp
    @if($sidebars->count())
        <ul id="sidebar_menu">
            @foreach($sidebars as $key => $section)
                <span class="menu_seperator">
                    {{__(@$section->backendMenu->name)}}
                </span>
                @if($section->children->count())
                    @foreach($section->children as $menu)
                        @if(!@$menu->backendMenu->module or isModuleActive(@$menu->backendMenu->module))
                            @if(@$menu->backendMenu->route == 'payment_gateway.index' && auth()->user()->role->type == 'seller' && !app('general_setting')->seller_wise_payment)
                                @continue
                            @elseif(permissionCheck(@$menu->backendMenu->route))
                                <li class="{{spn_active_link(childrenRoute($menu))}}">
                                    <a href="
                                    
                                        @if(\Illuminate\Support\Facades\Route::has(@$menu->backendMenu->route) && !$menu->children->count())
                                            @if(@$menu->backendMenu->route == 'my-wallet.index')
                                                @if(auth()->user()->role->type == 'seller')
                                                    {{route(@$menu->backendMenu->route, 'seller')}}
                                                @else
                                                    {{route(@$menu->backendMenu->route, 'admin')}}
                                                @endif
                                            @else
                                                {{route(@$menu->backendMenu->route)}}
                                            @endif
                                             
                                         @else 
                                            javascript:void(0) 
                                         @endif" class="@if($menu->children->count()) has-arrow @endif" aria-expanded="false">
                                        <div class="nav_icon_small">
                                            <span class="{{@$menu->backendMenu->icon?@$menu->backendMenu->icon:'fas fa-users'}}"></span>
                                        </div>
                                        <div class="nav_title">
                                            <span>{{__($menu->backendMenu->name)}}</span>
                                            {{-- @if (config('app.sync'))
                                                @if(@$menu->backendMenu->module != 'MultiVendor' && @$menu->backendMenu->module != null)
                                                        <span class="demo_addons">Addon</span>
                                                @elseif(@$menu->backendMenu->module == 'MultiVendor' && auth()->user()->role->type != 'seller')
                                                    <span class="demo_addons">Addon</span>
                                                @endif
                                            @endif --}}
                                        </div>
                                    </a>
                                    @if($menu->children->count())
                                        <ul class="mm-collapse">
                                            @foreach($menu->children as $submenu)
                                                @if(app('theme')->folder_path == 'amazy')
                                                    @if(@$submenu->backendMenu->route == 'frontendcms.features.index' || @$submenu->backendMenu->route == 'frontendcms.about-us.index')
                                                        @continue
                                                    @endif
                                                @elseif(app('theme')->folder_path == 'default')
                                                    @if(@$submenu->backendMenu->route == 'frontendcms.ads_bar.index' || @$submenu->backendMenu->route == 'frontendcms.promotionbar.index' || @$submenu->backendMenu->route == 'frontendcms.login_page')
                                                        @continue
                                                    @endif
                                                @endif
                                                @if(!@$submenu->backendMenu->module or isModuleActive(@$submenu->backendMenu->module))
                                                    @if(permissionCheck($submenu->backendMenu->route))
                                                    
                                                        <li>
                                                            <a href="
                                                                @if(\Illuminate\Support\Facades\Route::has($submenu->backendMenu->route) && !$submenu->children->count())
                                                                    @if(@$submenu->backendMenu->route == 'my-wallet.index')
                                                                        @if(auth()->user()->role->type == 'seller')
                                                                            {{route(@$submenu->backendMenu->route, 'seller')}}
                                                                        @else
                                                                            {{route(@$submenu->backendMenu->route, 'admin')}}
                                                                        @endif
                                                                    
                                                                    @else
                                                                        {{route(@$submenu->backendMenu->route)}}
                                                                    @endif
                                                                @else 
                                                                    javascript:void(0)
                                                                @endif" class="{{spn_active_link(childrenRoute($submenu), 'active')}} @if(@$submenu->children->count()) has-arrow @endif">{{__(@$submenu->backendMenu->name)}}</a>
                                                            @if(@$submenu->children->count())
                                                                <ul class="metis_submenu">
                                                                    @foreach($submenu->children as $subsubmenu)
                                                                        <li>
                                                                            <a href="@if(\Illuminate\Support\Facades\Route::has(@$subsubmenu->backendMenu->route)) {{route(@$subsubmenu->backendMenu->route)}} @else javascript:void(0) @endif"> {{__(@$subsubmenu->backendMenu->name)}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                        
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
    @endif

</nav>
<!-- sidebar part end -->
