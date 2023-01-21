@php
    $urlCurrent = URL::current();
    $children->loadMissing(['slugable', 'activeChildren:id,name,parent_id', 'activeChildren.slugable']);
@endphp

<span class="sub-toggle @if ($isActive) active @endif"><i class="icon-angle"></i></span>
<ul class="sub-menu" @if ($isActive || in_array($urlCurrent, collect($children->toArray())->pluck('url')->toArray())) style="display:block" @endif>
    @foreach($children as $category)
        <li class="@if($urlCurrent == $category->url) current-menu-item @endif @if ($category->activeChildren->count()) menu-item-has-children @endif"><a href="{{ $category->url }}">{{ $category->name }}</a>
            @if ($category->activeChildren->count())
                @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.sub-categories', ['children' => $category->activeChildren, 'isActive' => false])
            @endif
        </li>
    @endforeach
</ul>
