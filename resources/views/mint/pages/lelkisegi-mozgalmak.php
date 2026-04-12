<mint-extend path="layout/inner.php" :subtitle="{$title . ' | '}" :page-title="{$title}">

    <mint-section name="header">
        <link rel="canonical" href="@route('portal.spiritual_movements')" />
        <mod-og-image />
    </mint-section>

    <div class="text-center fst-italic mb-5 text-secondary">
        {{ $description }}
    </div>

    <div class="movement-list">
        <a x:foreach="{ $spiritualMovements as $spiritualMovement }"
            href="{{ $spiritualMovement->getUrl() }}"
            class="movement-card">
            <div class="movement-inner">
                <div class="movement-image">
                    <img src="{{ $spiritualMovement->image_url }}"
                        alt="{{ $spiritualMovement->name }}">
                </div>
                <div class="movement-content">
                    <h3 class="movement-title">{{ $spiritualMovement->name }}</h3>
                    <p class="movement-text">{{ $spiritualMovement->excerpt() }}</p>
                    <div class="movement-footer">
                        <span class="movement-count">
                            {{ $spiritualMovement->groups_count ?? 0 }} közösség
                        </span>
                        <span class="movement-link">
                            Megnézem
                            <span class="arrow-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12H19M19 12L13 6M19 12L13 18"
                                        stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </div>

</mint-extend>
