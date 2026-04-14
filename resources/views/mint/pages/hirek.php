<mint-extend path="layout/inner.php" :subtitle="Hírek, események | " :page-title="Hírek, események">

    <mint-section name="header"></mint-section>

    <div class="news-list">
        @foreach($news as $new)
        <a href="{{ $new->getUrl() }}" class="news-list__row text-decoration-none">
            <div class="card mb-3 shadow rounded">
                <div class="row g-0">
                    <div class="p-2 align-middle text-center col-auto">
                        <img @lazySrc() data-src="{{ $new->header_image }}"
                            data-srcset="{{ $new->header_image }}"
                            alt="{{ $new->title }}"
                            class="align-middle lazy news-list__thumb">
                    </div>
                    <div class="p-3 flex-grow-1 text-center text-md-start col">
                        <h3 class="h5 mb-2"><b class="card-title">{{ $new->title }}</b></h3>
                        <div class="text-muted d-flex justify-content-between align-items-center flex-column flex-md-row mb-2 small">
                            <span>{{ carbon($new->created_at)->format('Y. m. d.') }}</span>
                        </div>
                        <div class="card-text">{{ $new->excerpt(40, '...') }}</div>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-5">
        {{ $news->renderSmallPager() }}
    </div>

</mint-extend>
