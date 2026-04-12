<div class="community-card h-100{{ $lifecycle_cancelled ? ' community-card--event-cancelled' : '' }}">

    <a href="{{ $event['url'] }}" class="community-image{{ $lifecycle_cancelled ? ' community-image--cancelled' : '' }}">

        <img @lazySrc("/images/placeholder_rect.webp")
            data-src="{{ $event['featured_image'] }}"
            data-srcset="{{ $event['featured_image'] }}"
            alt="{{ $event['name'] }}"
            class="lazy">

        <div x:if="{ $lifecycle_cancelled }" class="event-card-cancelled-overlay" aria-hidden="true">
            <span class="event-card-cancelled-overlay__band"></span>
            <span class="event-card-cancelled-overlay__text">@lang('event_life_cycle.cancelled')</span>
        </div>

        <div x:if="{ $show_tags }" class="community-tags">
            <span x:foreach="{ $tags_preview as $tag }" class="community-badge">
                {{ $tag }}
            </span>
        </div>

    </a>
    <div class="community-body">

        <div class="community-location">
            <i class="fas fa-map-marker-alt"></i>
            {{ $event['location_name'] ?? $event['address'] }}
            <div x:if="{ !empty($event['location_name']) && !empty($event['address']) }" class="community-city">
                {{ $event['address'] }}
            </div>
        </div>

        <div class="community-age">
            <i class="fas fa-calendar-alt"></i>
            @if($all_day)
                {{ $event['starts_at']->format('Y/m/d') }}
                @if($show_all_day_end)
                    - {{ $event['ends_at']->format('Y/m/d') }}
                @endif
            @else
                {{ $event['starts_at']->format('Y/m/d H:i') }}
                @if($show_time_end)
                    - {{ $event['ends_at']->format('H:i') }}
                @endif
            @endif
        </div>

        <h3 class="community-title">
            {{ $event['name'] }}
        </h3>

        <a href="{{ $event['url'] }}" class="community-link">
            Részletek
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
