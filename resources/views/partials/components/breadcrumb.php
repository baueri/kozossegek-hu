<nav aria-label="breadcrumb">
    <ol class="breadcrumb flex-nowrap" itemscope itemtype="http://schema.org/BreadcrumbList">
        @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item flex-shrink-0" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            @if(!empty($breadcrumb['url']))<a id="{{ $breadcrumb['url'] }}" href="{{ $breadcrumb['url'] }}" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">@endif
                <span itemprop="name">{{ $breadcrumb['name'] }}</span>
            @if(!empty($breadcrumb['url']))</a>@endif
            <meta itemprop="position" content="{{ $breadcrumb['position'] }}">
        </li>
        @endforeach
    </ol>
</nav>