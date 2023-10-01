<div id="kozossegek" class="columns is-multiline">
    @foreach($groups as $i => $group)
        <div class="column is-3">
            <div class="card is-always-shady is-flex is-flex-direction-column">
                <div class="card-image">
                    <figure class="image is-5by3">
                        <img src="{{ $group->getThumbnail() }}" alt="{{ $group->city }}" style="object-fit: cover">
                    </figure>
                </div>
                <div class="card-content is-flex-grow-1">
                    <div class="media-content">
                        <div class="tags">
                            @if($group->tags)
                                @foreach($group->tags as $tag)
                                    <small class="tag is-rounded is-info is-light" title="{{ $tag['tag_name'] }}" aria-label="{{ $tag['tag_name'] }}">{{ $tag['tag_name'] }}</small>
                                @endforeach
                            @endif
                        </div>
                        <p class="title is-4">{{ $group->name }}</p>
                        <p class="subtitle is-6 has-text-grey">{{ $group->city . ($group->district ? ', ' . $group->district : '')  }}</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <span class="card-footer-item">{{ $group->ageGroup() }}</span>
                    <span class="card-footer-item">{{ $group->occasionFrequency() }}</span>
                    <a href="{{ $group->url() }}" class="card-footer-item has-background-info has-text-white">Megnézem</a>
                </footer>
            </div>
        </div>
    @endforeach
</div>