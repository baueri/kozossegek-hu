<nav class="breadcrumb has-background-light p-3" aria-label="breadcrumb">
    <ul itemscope itemtype="http://schema.org/BreadcrumbList">
        @foreach($breadcrumbs as $breadcrumb)
        <li @if(!empty($breadcrumb['last']))class="is-active"@endif itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a id="{{ $breadcrumb['url'] }}" href="{{ $breadcrumb['url'] }}" itemscope="" itemtype="http://schema.org/Thing" itemprop="item">
                <span itemprop="name">{{ $breadcrumb['name'] }}</span>
            </a>
            <meta itemprop="position" content="{{ $breadcrumb['position'] }}">
        </li>
        @endforeach
    </ul>
</nav>