<ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
    @foreach ($crumbs = Theme::breadcrumb()->getCrumbs() as $i => $crumb)
        @if ($i != (count($crumbs) - 1))
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ $crumb['url'] }}">
                    <span itemprop="item">{!! BaseHelper::clean($crumb['label']) !!}</span>
                    <span class="extra-breadcrumb-name"></span>
                </a>
                <meta itemprop="name" content="{{ $crumb['label'] }}" />
                <meta itemprop="position" content="{{ $i + 1}}" />
            </li>
        @else
            <li aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span>{!! BaseHelper::clean($crumb['label']) !!}</span>
                <meta itemprop="name" content="{{ $crumb['label'] }}" />
                <meta itemprop="position" content="{{ $i + 1}}" />
            </li>
        @endif
    @endforeach
</ul>
