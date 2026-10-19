<mint-extend path="layout/inner.php" :subtitle="{$entry->title . ' | '}" :page-title="{$entry->title}">

    <mint-section name="header">
        <link rel="canonical" href="{{ $entry->getUrl() }}" />
        <meta name="description" content="{{ $entry->excerpt() }}" />
        @if($entry->header_image)
        <mod-og-image :src="{ $entry->featuredImageUrl() }" />
        @endif
    </mint-section>

    @if($entry->header_image)
    <img src="{{ $entry->header_image }}" alt="{{ $entry->title }}" class="w-100 rounded mb-4" style="object-fit: cover; max-height: 420px;">
    @endif
    <h1 class="h2 mb-2"><b>{{ $entry->title }}</b></h1>
    <div class="mb-4 text-muted small">{{ carbon($entry->created_at)->format('Y. m. d.') }}</div>
    <div class="news-entry-body">
        {{ $entry->content }}
    </div>

</mint-extend>
