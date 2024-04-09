@if($news && $display_news)
    <div class="container">
        <div class="pb-4 pb-sm-5">
            <h2 class="title-secondary mb-4 mb-sm-5 text-center">Hírek, események</h2>
            <div class="row">
                @foreach($news as $new)
                <div class="col-lg-4 col-12 mb-4">
                    <div class="card shadow rounded h-100">
                        <a href="{{ $new->getUrl() }}" class="spiritual-movement-row text-secondary text-decoration-none">
                            <img @lazySrc() data-src="{{ $new->header_image }}"
                                 data-srcset="{{ $new->header_image }}"
                                 alt="{{ $new->name }}"
                                 class="card-img-top lazy"
                                 style="height: 200px; object-fit: cover; aspect-ratio: 16 / 9;"
                            >
                            <div class="card-body">
                                <h4 class="card-title text-truncate text-center text-md-left mb-3 mb-md-0 font-weight-bold">{{ $new->title }}</h4>
                                <div class="text-muted text-md-left d-flex justify-content-between align-items-center flex-column flex-md-row mb-3 ">
                                    <span style="font-size: 14px">{{ carbon($new->created_at)->format('Y. m. d.') }}</span>
                                </div>
                                <div class="card-text" style="font-size: 16px">{{ $new->excerpt(25, '...') }}</div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-right">
                <a href="@route('portal.blog')" class="">További hírek >></a>
            </div>
        </div>
    </div>
@endif