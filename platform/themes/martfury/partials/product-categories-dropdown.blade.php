@foreach ($categories as $category)
    <li @if ($category->activeChildren->count()) class="menu-item-has-children has-mega-menu" @endif>
        <a href="{{ $category->url }}">
            @if ($category->getMetaData('icon_image', true))
                <img src="{{ RvMedia::getImageUrl($category->getMetaData('icon_image', true)) }}" alt="{{ $category->name }}" width="18" height="18">
            @elseif ($category->getMetaData('icon', true))
                <i class="{{ $category->getMetaData('icon', true) }}"></i>
            @endif {{ $category->name }}
        </a>
        @if ($category->activeChildren->count())
            <span class="sub-toggle"></span>
            <div class="mega-menu" @if ($category->activeChildren->count() == 1) style="min-width: 250px;" @endif>
                @foreach($category->activeChildren as $childCategory)
                    <div class="mega-menu__column">
                        @if ($childCategory->activeChildren->count())
                            <a href="{{ $childCategory->url }}"><h4>{{ $childCategory->name }}<span class="sub-toggle"></span></h4></a>
                            <ul class="mega-menu__list">
                                @foreach($childCategory->activeChildren as $item)
                                    <li><a href="{{ $item->url }}">{{ $item->name }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $childCategory->url }}"><h4>{{ $childCategory->name }}</h4></a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </li>
@endforeach
