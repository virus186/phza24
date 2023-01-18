
<ul class="submenu">
    @foreach($element->childs as $key => $element)
        @if($element->type == 'page')
            @if(!isModuleActive('Lead') && $element->page->module == 'Lead')
                @continue
            @endif
            @if(!isModuleActive('MultiVendor') && $element->page->slug == 'merchant' || !isModuleActive('MultiVendor') && $element->page->module == 'MultiVendor')
                @continue
            @endif
            <li class="submenu_active">
                <a href="{{ url(@$element->page->slug) }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{ ucfirst(textLimit($element->title, 20)) }} @if($element->childs->count()) <i class="ti-angle-down"></i> @endif</a>
            </li>
        @elseif($element->type == 'category')
            @if($element->childs->count() > 0)
                <li class="submenu_active"><a href="{{route('frontend.category-product',['slug' => $element->category->slug, 'item' =>'category'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}} <i class="ti-angle-down"></i></a>
                    @include(theme('partials._menu_chield'), ['element' => $element])
                </li>
            @else
                <li class="">
                    <a href="{{route('frontend.category-product',['slug' => $element->category->slug, 'item' =>'category'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
                </li>
            @endif
        @elseif($element->type == 'brand')
            <li class="">
                <a href="{{route('frontend.category-product',['slug' => $element->brand->slug, 'item' =>'brand'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
            </li>
        @elseif($element->type == 'tag')
            <li class="">
                <a href="{{route('frontend.category-product',['slug' => $element->tag->name, 'item' =>'tag'])}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
            </li>
        @elseif($element->type == 'product' && @$element->product)
            <li class="">
                <a href="{{singleProductURL(@$element->product->seller->slug, @$element->product->slug)}}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
            </li>
        @elseif($element->type == 'link')
            <li class="">
                <a href="{{ $element->link }}" target="{{$element->is_newtab == 1?'_blank':''}}">{{textLimit($element->title,20)}}</a>
            </li>
        @endif
    @endforeach
</ul>