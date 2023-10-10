<p class="mb-4">
    Összes találat: {{ $total }}
</p>
<div id="kozossegek" class="columns is-multiline">
    @foreach($groups as $i => $group)
        <div class="column is-3">
            <div class="card is-always-shady is-flex is-flex-direction-column">
                <div class="card-image">
                    <a href="{{ $group->url() }}">
                        <figure class="image is-5by3">
                            <img src="/images/placeholder.jpg" data-src="{{ $group->getThumbnail() }}" data-srcset="{{ $group->getThumbnail() }}" alt="{{ $group->city }}" style="object-fit: cover" class="lazy">
                        </figure>
                    </a>
                </div>
                <div class="card-content is-flex-grow-1">
                    <div class="media-content is-flex is-flex-direction-column h-100">
                        <div class="mb-4">
                            <a href="{{ $group->url() }}"><span class="title is-5_5">{{ $group->name }}</span><br/>
                                <span class="menu-label is-7 has-text-grey-dark">{{ $group->city . ($group->district ? ', ' . $group->district : '')  }}</span>
                            </a>
                        </div>
                        <div class="tags is-flex-grow-1 is-justify-content-start is-align-content-start	">
                            <div x:foreach="$group->tags as $tag" class="tag is-rounded is-info is-light pr-2 pl-2" title="{{ $tag['tag_name'] }}"
                                 aria-label="{{ $tag['tag_name'] }}"><com:Icon class="hashtag mr-1"/> {{ $tag['tag_name'] }}
                            </div>
                        </div>
                        <div>
                            <p class="menu-label has-text-grey-dark"><b>korosztály: </b>{{ $group->ageGroup() }}</p>
                            <p class="menu-label has-text-grey-dark"><b>alkalmak: </b>{{ $group->occasionFrequency() }}</p>
                        </div>
                    </div>
                </div>
                <footer class="card-footer">
                    <div class="card-footer-item p-0">
                        <a href="{{ $group->url() }}" class="menu-label p-4 has-text-grey-dark is-uppercase is-full-width has-text-centered">Megnézem</a>
                    </div>
                </footer>
            </div>
        </div>
    @endforeach
</div>