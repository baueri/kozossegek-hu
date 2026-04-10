<div class="community-card h-100{{ ($event['lifecycle'] ?? '') === 'cancelled' ? ' community-card--event-cancelled' : '' }}">

    <!-- KÉP -->
    <a href="{{ $event['url'] }}" class="community-image{{ ($event['lifecycle'] ?? '') === 'cancelled' ? ' community-image--cancelled' : '' }}">

        <img @lazySrc("/images/placeholder_rect.webp")
            data-src="{{ $event['featured_image'] }}"
            data-srcset="{{ $event['featured_image'] }}"
            alt="{{ $event['name'] }}"
            class="lazy">

        @if(($event['lifecycle'] ?? '') === 'cancelled')
        <div class="event-card-cancelled-overlay" aria-hidden="true">
            <span class="event-card-cancelled-overlay__band"></span>
            <span class="event-card-cancelled-overlay__text">@lang('event_life_cycle.cancelled')</span>
        </div>
        @endif

        <!-- TAG -->
        <div class="community-tags">
            @if(!empty($event['tags']))
            <?php $tags = array_slice($event['tags'], 0, 3); ?>
            @foreach($tags as $tag)
            <span class="community-badge">
                {{ $tag }}
            </span>
            @endforeach
            @endif
        </div>

    </a>

    <div class="community-body">

        <!-- HELYSZÍN -->
        <div class="community-location">
            <i class="fas fa-map-marker-alt"></i>
            {{ $event['location_name'] ?? $event['address'] }}
        </div>

        <!-- DÁTUM -->
        <div class="community-age">
            <i class="fas fa-calendar-alt"></i>

            @if($event['all_day'])
                {{ $event['starts_at']->format('Y/m/d') }}
                @if($event['ends_at'] && $event['ends_at']->format('Y-m-d') !== $event['starts_at']->format('Y-m-d'))
                    - {{ $event['ends_at']->format('Y/m/d') }}
                @endif
            @else
                {{ $event['starts_at']->format('Y/m/d H:i') }}
                @if($event['ends_at'])
                    - {{ $event['ends_at']->format('H:i') }}
                @endif
            @endif
        </div>

        <!-- CÍM -->
        <h3 class="community-title">
            {{ $event['name'] }}
        </h3>

        <!-- LINK -->
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
