<nav aria-label="breadcrumb">
    <ol class="breadcrumb small d-none d-md-flex" itemscope itemtype="http://schema.org/BreadcrumbList">
        @foreach($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            @if(!empty($breadcrumb['url']))<a id="{{ $breadcrumb['url'] }}" href="{{ $breadcrumb['url'] }}" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">@endif
                <span itemprop="name">{{ $breadcrumb['name'] }}</span>
            @if(!empty($breadcrumb['url']))</a>@endif
            <meta itemprop="position" content="{{ $breadcrumb['position'] }}">
        </li>
        @endforeach
    </ol>
</nav>