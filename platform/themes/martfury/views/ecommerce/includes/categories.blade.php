@php
    $categories->loadMissing(['slugable', 'activeChildren:id,name,parent_id', 'activeChildren.slugable']);

    if (!empty($categoriesRequest)) {
        $categories = $categories->whereIn('id', $categoriesRequest);
    }
@endphp

<ul class="ps-list--categories">
    @if (!empty($categoriesRequest))
        <li>
            <a href="{{ route('public.products') }}">
                <i class="icon-chevron-left"></i> <span>{{ __('All categories') }}</span>
            </a>
        </li>
    @endif

    @foreach($categories as $category)
        @php
            $isActive = $urlCurrent == $category->url || (!empty($categoriesRequest && in_array($category->id, $categoriesRequest))) || ($loop->first && $categoriesRequest && $categories->count() == 1 && $category->activeChildren->count());
        @endphp
        <li class="@if ($isActive) current-menu-item @endif @if ($category->activeChildren->count()) menu-item-has-children @endif">
            <a href="{{ $category->url }}">{{ $category->name }}</a>
            @if ($category->activeChildren->count())
                @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.sub-categories', ['children' => $category->activeChildren, 'isActive' => $isActive])
            @endif
        </li>
    @endforeach
</ul>
