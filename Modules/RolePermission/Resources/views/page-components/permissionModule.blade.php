<div class="single_role_blocks">
    <div class="single_permission" id="{{ $Module->id }}">
        <div class="permission_header d-flex align-items-center justify-content-between">
            <div>
                <input type="checkbox"  name="module_id[]" value="{{ $Module->id }}" id="Main_Module_{{ $key }}" class="common-radio permission-checkAll main_module_id_{{ $Module->id }}" {{ $role->permissions->contains('id',$Module->id) ? 'checked' : '' }} >
                <label for="Main_Module_{{ $key }}">{{ $Module->name }}</label>
            </div>
            <div class="arrow collapsed" data-toggle="collapse" data-target="#Role{{ $Module->id }}"></div>
        </div>

        <div id="Role{{ $Module->id }}" class="collapse">
            <div  class="permission_body">
                <ul>
                    @foreach ($SubMenuList->where('parent_id',$Module->id) as $SubMenu)
                        @if(isModuleActive('MultiVendor') && $SubMenu->name == 'Company Reviews')
                            @continue
                        @endif

                        @if(app('theme')->folder_path == 'amazy')
                            @if($SubMenu->route == 'frontendcms.features.index')
                                @continue
                            @endif
                        @elseif(app('theme')->folder_path == 'default')
                            @if($SubMenu->route == 'frontendcms.ads_bar.index' || $SubMenu->route == 'frontendcms.promotionbar.index' || $SubMenu->route == 'frontendcms.login_page')
                                @continue
                            @endif
                        @endif
                        
                        @if(!$SubMenu->module or isModuleActive($SubMenu->module))
                            <li>
                                <div class="submodule">
                                    <input id="Sub_Module_{{ $SubMenu->id }}" name="module_id[]" value="{{ $SubMenu->id }}"  class="infix_csk common-radio  module_id_{{ $Module->id }} module_link" {{ $role->permissions->contains('id',$SubMenu->id) ? 'checked' : '' }}  type="checkbox" >

                                    <label for="Sub_Module_{{ $SubMenu->id }}">
                                        @if($SubMenu->name == 'Seller Reviews') 
                                            @if(isModuleActive('MultiVendor')) 
                                                {{ $SubMenu->name }} 
                                            @else 
                                                Company Reviews
                                            @endif 
                                        @elseif($SubMenu->name == 'Inhouse Product Sale')
                                            @if(isModuleActive('MultiVendor')) 
                                                {{ $SubMenu->name }} 
                                            @else 
                                                Product Sale
                                            @endif 
                                        @else 
                                            {{ $SubMenu->name }} 
                                        @endif</label>
                                    <br>
                                </div>

                                <ul class="option">
                                    @foreach ($ActionList->where('parent_id',$SubMenu->id) as $action)
                                        @if(!$action->module or isModuleActive($action->module))
                                            <li>
                                                <div class="module_link_option_div" id="{{ $SubMenu->id }}">
                                                    <input id="Option_{{  $action->id }}" name="module_id[]" value="{{  $action->id }}"  class="infix_csk common-radio module_id_{{ $Module->id }} module_option_{{ $Module->id }}_{{ $SubMenu->id }} module_link_option" {{ $role->permissions->contains('id',$action->id) ? 'checked' : ''  }}  type="checkbox" >
                                                    <label for="Option_{{  $action->id }}">{{$action->name}}</label>
                                                    <br>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
