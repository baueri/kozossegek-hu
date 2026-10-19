<mint-extend path="layout/portal.php" :subtitle="{$spiritualMovement->name . ' | '}" :body-class="movement-page">

    <mint-section name="header">
        <mod-og-image :src="{ $spiritualMovement->image_url }" />
    </mint-section>

    <section class="page-header">
        <div class="container">

            <div class="movement-hero-inner">
                <div class="movement-hero-image">
                    <img src="{{ $spiritualMovement->image_url }}"
                        alt="{{ $spiritualMovement->name }}">
                </div>
                <div class="movement-hero-content">
                    <h1 class="movement-title">{{ $title }}</h1>
                    <a x:if="{ $spiritualMovement->website }"
                        href="{{ $spiritualMovement->website }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="movement-website">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                        {{ $spiritualMovement->website }}
                    </a>
                </div>
            </div>

        </div>
    </section>

    <div class="container inner">

        <div class="movement-description">
            {{ $spiritualMovement->description }}
        </div>

        <div x:if="{ $groups && $groups->isNotEmpty() }">
            <h2 class="movement-section-title text-center">
                A(z) {{ $spiritualMovement->name }} közösségei:
            </h2>
            <div class="row" id="kozossegek-list">
                <div x:foreach="{ $groups as $group }" class="col-xl-4 col-md-6 mb-3">
                    <mod-kozosseg-card :group="{ $group }" />
                </div>
            </div>
        </div>

    </div>

</mint-extend>
