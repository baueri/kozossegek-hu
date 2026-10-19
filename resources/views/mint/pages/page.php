<mint-extend path="layout/inner.php" :props="{$subtitle, $pageTitle}">

    <mint-section name="header">
        <link rel="canonical" href="{{ $page->getUrl() }}" />
        <meta name="description" content="{{ $page->excerpt() }}" />

        <mod-og-image x:if="{!empty($page->header_image)}" :src="$page->featuredImageUrl()" />

    </mint-section>

    <div class="page-content">
        {{ $page->content }}
    </div>

</mint-extend>
