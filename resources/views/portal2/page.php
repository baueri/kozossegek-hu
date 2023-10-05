<com:section name="portal2.header">
    <link rel="canonical" href="{{ $page->getUrl() }}" />
    <meta name="description" content="{{ $page->excerpt() }}" />
</com:section>

<com:section name="subtitle">
    {{ $page->title }} |
</com:section>

<com:template extends="portal2.main">
    <com:featured-header>
        <h1 class="title has-text-white">{{ $page->title }}</h1>
    </com:featured-header>
    <div class="container is-max-desktop inner p-4 page">
        <div>
            {{ $page->content }}
        </div>
    </div>
</com:template>
