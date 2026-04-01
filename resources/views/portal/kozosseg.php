@section('header')
    <meta name="keywords" content="{{ $keywords }}" />
    <meta name="description" content="{{ $group->name }}" />
    <meta name="thumbnail" content="{{ $group->getThumbnail() }}" />
    <meta property="og:url" content="{{ $group->url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="kozossegek.hu - {{ $group->name }}" />
    <meta property="og:description" content="{{ $group->excerpt(20) }}" />
    @og_image($group->getThumbnail())
@endsection

@extends('portal')


<section class="group-hero-modern">
    <div class="container">

        <div class="back-link">
            <a href="@route('portal.groups')">← Vissza a közösségekhez</a>
        </div>

        {{ $group->getBreadCrumb() }}

        <div class="group-header-grid">

            <!-- BAL -->
            <div class="group-main">

                <!-- TAG-ek -->
                <div class="group-tags-modern">
                    @foreach($group->tags as $tag)
                        <span class="tag-pill">{{ $tag->translate() }}</span>
                    @endforeach
                </div>

                <!-- CÍM -->
                <h1 class="group-title-modern">
                    {{ $group->name }}
                </h1>

                <!-- ALINFO -->
                @if($institute)
                    <div class="group-institute">
                        {{ $institute->name }}
                    </div>
                @endif

                <div class="group-meta-row">
                    <span>📍 {{ $group->city }}</span>
                    <span>🕒 {{ $group->occasionFrequency() }}</span>
                </div>

                <!-- BEMUTATKOZÁS -->
                @if($group->description)
                <div class="group-card">
                    <h3>Bemutatkozás</h3>
                    <p>{{ $group->description }}</p>
                </div>
                @endif

                <!-- INFO BLOKKOK -->
                <div class="group-info-grid">

                    <div class="info-card">
                        <span class="info-label">Közösségvezető(k)</span>
                        <strong>{{ $group->group_leaders }}</strong>
                    </div>

                    @if($group->join_mode)
                    <div class="info-card">
                        <span class="info-label">Csatlakozás módja</span>
                        <strong>{{ $group->joinModeText() }}</strong>
                    </div>
                    @endif

                </div>

                <!-- CTA -->
                <div class="group-actions">
                    <button class="btn btn-orange open-contact-modal">
                        Kapcsolatfelvétel
                    </button>

                    <div class="share-btn">
                        @facebook_share_button($group->url())
                    </div>
                </div>

            </div>

            <!-- JOBB -->
            <div class="group-sidebar-modern">

                <div class="group-image-card">
                    <img src="{{ $group->getThumbnail() }}" alt="{{ $group->name }}">
                </div>

                <div class="group-side-card">
                    <h4>A közösség jellemzői</h4>

                    <div class="side-tags">
                        <span class="tag-pill">{{ $group->ageGroup() }}</span>
                        @foreach($group->tags as $tag)
                            <span class="tag-pill light">{{ $tag->translate() }}</span>
                        @endforeach
                    </div>

                    <div class="side-meta">
                        <div>🕒 {{ $group->occasionFrequency() }}</div>
                        <div>📍 {{ $group->city }}</div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>


<div class="container inner">

    @if($similar_groups?->isNotEmpty())

        <h2 class="section-title">Hasonló közösségek</h2>

        <div class="row" id="kozossegek-list">
            @foreach($similar_groups as $similarGroup)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    @include('portal.partials.kozosseg_card', ['group' => $similarGroup])
                </div>
            @endforeach
        </div>

    @endif

</div>

<style>
.group-hero-modern {
    padding: 30px 0 10px;
}

.back-link a {
    color: #64748b;
    font-size: 0.9rem;
    text-decoration: none;
}

.group-header-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    margin-top: 20px;
}

.group-title-modern {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    margin: 10px 0;
}

.group-tags-modern {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.tag-pill {
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
}

.tag-pill.light {
    background: #e2e8f0;
}

.group-meta-row {
    display: flex;
    gap: 20px;
    color: #64748b;
    margin-bottom: 20px;
}

.group-card {
    background: #f8fafc;
    padding: 20px;
    border-radius: 1rem;
    margin-bottom: 20px;
}

.group-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.info-card {
    background: #f8fafc;
    padding: 15px;
    border-radius: 1rem;
}

.info-label {
    font-size: 0.75rem;
    color: #94a3b8;
    display: block;
}

.group-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

.group-sidebar-modern {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.group-image-card {
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.group-image-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.group-side-card {
    background: #f8fafc;
    padding: 20px;
    border-radius: 1.5rem;
}

.side-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin: 10px 0;
}

.side-meta {
    color: #64748b;
    font-size: 0.9rem;
}

.section-title {
    font-family: 'Playfair Display', serif;
    margin: 40px 0 20px;
}

@media (max-width: 768px) {
    .group-header-grid {
        grid-template-columns: 1fr;
    }
}
</style>