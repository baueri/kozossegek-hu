<div class="community-card h-100">

    <!-- KÉP -->
    <a href="{{ $group['url'] }}" class="community-image">

        <img @lazySrc("/images/placeholder_rect.webp")
            data-src="{{ $group['thumbnail'] }}"
            data-srcset="{{ $group['thumbnail'] }}"
            alt="{{ $group['city'] }}"
            class="lazy">

        <!-- TAG -->
        <div class="community-tags">
            @if($group['tags'])
            <?php $tags = array_slice($group['tags'], 0, 3); ?>
            @foreach($tags as $tag)
            <span class="community-badge">
                {{ $tag }}
            </span>
            @endforeach
            @endif
        </div>
    </a>

    <div class="community-body">
        <div class="community-location">
            <i class="fas fa-map-marker-alt"></i>
            {{ $group['city'] . ($group['district'] ? ', ' . $group['district'] : '') }}
        </div>

        @if(!empty($group['age_group_text']))
        <div class="community-age">
            <i class="fas fa-user"></i>
            {{ implode(', ', $group['age_group_text']) }}
        </div>
        @endif

        <h3 class="community-title">
            {{ $group['name'] }}
        </h3>

        <a href="{{ $group['url'] }}" class="community-link">
            Adatlap megtekintése
            <span class="arrow-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12H19M19 12L13 6M19 12L13 18"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </a>

    </div>

</div>