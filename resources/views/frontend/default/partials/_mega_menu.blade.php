<section class="banner_part pt-5">
    <div class="container">
        <div class="row">
            @if($menus->where('menu_type', 'multi_mega_menu')->count())
                <div class="col-xl-3 col-lg-4 col-md-6 d-none d-lg-block">
                    <div class="side-menu animate-dropdown outer-bottom-xs">
                        <nav class="spn megamenu-horizontal">
                            <ul class="nav">
                                @foreach($menus->where('status', 1) as $key => $menu)
                                    @if($menu->menu_type == 'multi_mega_menu')
                                        <h4>{{ textLimit($menu->name, 25) }}</h4>
                                        <ul class="nav nav_width">
                                            @foreach(@$menu->menus as $key => $menu)
                                                @if(@$menu->menu->menu_type == 'mega_menu' && @$menu->menu->status == 1)
                                                    <li class="dropdown menu-item">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                        <i class="{{@$menu->menu->icon}}"></i>{{ textLimit(@$menu->menu->name, 25) }}</a>
                                                        <ul class="dropdown-menu mega-menu">
                                                        <li class="spn-content">
                                                            <div class="row ">
                                                                <div class="col-sm-12 col-md-12 col-xl-8">
                                                                    <div class="row">
                                                                        @if(@$menu->menu->columns->count())
                                                                            @php
                                                                                $is_same = 1;
                                                                                $column_size = $menu->menu->columns[0]->size;

                                                                                foreach($menu->menu->columns as $key => $column){
                                                                                    if($column->size != $column_size){
                                                                                    $is_same =0;
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            @foreach(@$menu->menu->columns as $key => $column)
                                                                                <div class="
                                                                                    @if($column->size == '1/1')
                                                                                    col-sm-12 col-md-12 col-lg-12 col-xl-12
                                                                                    @elseif($column->size == '1/2')
                                                                                    col-sm-12 col-md-6 col-lg-6 col-xl-6
                                                                                    @elseif($column->size == '1/3')
                                                                                    col-sm-6 col-md-6 col-lg-4 col-xl-4
                                                                                    @elseif($column->size == '1/4')
                                                                                    col-sm-6 col-md-6 col-lg-3 col-xl-3
                                                                                    @endif
                                                                                ">
                                                                                    <ul class="links list-unstyled">
                                                                                        <li><span>{{ textLimit($column->column, 25) }}</span></li>
                                                                                        @foreach(@$column->elements as $key => $element)

                                                                                        @if($element->type == 'link')
                                                                                            <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="
                                                                                                @if($element->link != null)
                                                                                                {{url($element->link)}}
                                                                                                @else
                                                                                                javascript:void(0);
                                                                                                @endif
                                                                                            ">{{ textLimit($element->title, 25) }}</a></li>

                                                                                            @elseif($element->type == 'category' && $element->category->status == 1)
                                                                                                <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$element->category->slug, 'item' =>'category'])}}">
                                                                                                {{ ucfirst(textLimit($element->title, 25)) }}
                                                                                                </a></li>

                                                                                            @elseif(@$element->type == 'product' && $element->product)
                                                                                                <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{singleProductURL(@$element->product->seller->slug, $element->product->slug)}}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>
                                                                                            @elseif($element->type == 'brand' && $element->brand->status == 1)
                                                                                                <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$element->brand->slug, 'item' =>'brand'])}}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>

                                                                                            @elseif($element->type == 'page' && $element->page->status == 1)
                                                                                                @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                                                                                                    @continue
                                                                                                @endif
                                                                                                <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{ url(@$element->page->slug) }}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>

                                                                                            @elseif($element->type == 'tag')
                                                                                                <li><a target="{{$element->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$element->tag->name, 'item' =>'tag'])}}">{{ ucfirst(textLimit($element->title, 25)) }}</a></li>

                                                                                            @endif

                                                                                        @endforeach

                                                                                    </ul>
                                                                                </div>

                                                                            @endforeach
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                                @if(count(@$menu->menu->rightPanelData)> 0)
                                                                    <div class="col-sm-12 col-xl-4 d-none d-xl-block">
                                                                        <div class="mega_menu_product">
                                                                        @foreach(@$menu->menu->rightPanelData as $key => $item)
                                                                            @if($item->category->status == 1)
                                                                                <a target="{{$item->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$item->category->slug, 'item' =>'category'])}}" class="menu_product">
                                                                                    <div class="single_mega_menu_product">
                                                                                    <div class="media align-items-center">
                                                                                        <div class="media_img">
                                                                                        <img data-src="{{showImage(@$item->category->categoryImage->image?@$item->category->categoryImage->image:'frontend/default/img/default_category.png')}}" src="{{showImage(themeDefaultImg())}}" class="lazyload align-self-center"alt="{{@$item->category->name}}"/>
                                                                                        </div>
                                                                                        <div class="media-body">
                                                                                        <p>{{$item->title}}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    </div>
                                                                                </a>

                                                                                @if($key >=5)
                                                                                    @break
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if(count(@$menu->menu->bottomPanelData)> 0)
                                                                    <div class="col-lg-12 d-none d-xl-block">
                                                                        <div class="product_logo">
                                                                        @foreach(@$menu->menu->bottomPanelData as $key => $item)
                                                                            @if($item->brand->status == 1)
                                                                                <a target="{{$item->is_newtab == 1?'_blank':''}}" href="{{route('frontend.category-product',['slug' => @$item->brand->slug, 'item' =>'brand'])}}" class="single_product_logo">
                                                                                    <img data-src="{{ showImage(@$item->brand->logo ? @$item->brand->logo:'frontend/default/img/brand_image.png') }}" src="{{showImage(themeDefaultImg())}}" class="lazyload" alt="{{@$item->brand->name}}" />
                                                                                </a>
                                                                                @if($key >=7)
                                                                                    @break
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            <!-- /.col -->
                                                            </div>
                                                            <!-- /.row -->
                                                            </li>
                                                            <!-- /.spn-content -->
                                                        </ul>
                                                    <!-- /.dropdown-menu -->
                                                    </li>
                                                @endif
                                            @endforeach
                                            <!-- /.menu-item -->
                                        </ul>
                                    @endif
                                    <!-- /.menu-item -->
                                @endforeach
                            </ul>
                        <!-- /.nav -->
                        </nav>
                        <!-- /.megamenu-horizontal -->
                    </div>
                <!-- /.side-menu -->
                </div>
            @endif
            <div class="@if($menus->where('menu_type', 'multi_mega_menu')->count()) col-xl-9 col-lg-8 @else col-xl-12 col-lg-12 @endif">
                <x-slider-component :headers="$headers"/>
            </div>
        </div>
    </div>
</section>
