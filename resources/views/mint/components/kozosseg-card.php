<div class="community-card h-100">

    <a href="{{ $group['url'] }}" class="community-image">

        <img @lazySrc("/images/placeholder_rect.webp")
            data-src="{{ $group['thumbnail'] }}"
            data-srcset="{{ $group['thumbnail'] }}"
            alt="{{ $group['city'] }}"
            class="lazy">

        <div x:if="{ $show_tags }" class="community-tags">
            <span x:foreach="{ $tags_preview as $tag }" class="community-badge">
                {{ $tag }}
            </span>
            <span x:if="{ $extra_tags > 0 }" class="community-badge community-badge--more">+{{ $extra_tags }}</span>
        </div>
    </a>

    <div class="community-body">
        <div class="community-location">
            <i class="fas fa-map-marker-alt"></i>
            {{ $group['institute_name'] ?? ($group['city'] . ($group['district'] ? ', ' . $group['district'] : '')) }}
            <div x:if="{ !empty($group['institute_name']) }" class="community-city">
                {{ $group['city'] . ($group['district'] ? ', ' . $group['district'] : '') }}
            </div>
        </div>

        <div x:if="{ $show_age }" class="community-age">
            <i class="fas fa-user"></i>
            {{ $group['age_group_combined'] }}
        </div>

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
