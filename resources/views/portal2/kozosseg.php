<com:section name="portal2.header">
    <meta name="keywords" content="{{ $keywords }}" />
    <meta name="description" content="{{ $group->name }}" />
    <meta property="og:url"           content="{{ $group->url() }}" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="kozossegek.hu - {{ $group->name }}" />
    <meta property="og:description"   content="{{ $group->excerpt(20) }}" />
    <meta property="og:image"         content="{{ $group->getThumbnail() }}" />
    <meta property="og:locale"         content="hu_HU" />
    <link rel="canonical" href="{{ $group->url() }}" />
</com:section>

<com:template extends="portal2.main">
    <?php $nvr = 'a_' . substr(md5(time()), 0, 5); ?>
    <script>
        let nvr = "{{ $nvr }}";
    </script>
    <div class="container is-max-desktop mt-4 mb-4">
        {{ $group->getBreadCrumb() }}
    </div>
    <section>
        <div class="container is-max-desktop">
            @if($group->status == "inactive")
                <com:notification type="warning">
                    Ez a közösséged jelenleg <b>inaktív</b> állapotban van, ezért mások számára nem jelenik meg a találati listában, illetve közvetlenül se tudják megtekinteni az adatlapját. Amennyiben láthatóvá szeretnéd tenni, állítsd át az állapotát <b>aktívra</b> a <a href="{{ $group->getEditUrl() }}" title="szerkesztés">szerkesztési oldalon</a>.
                </com:notification>
            @endif

            <div class="columns">
                <div class="column is-3">
                    <figure class="image">
                        <img src="/images/default_thumbnail.jpg" data-src="{{ $group->getThumbnail() }}"
                             data-srcset="{{ $group->getThumbnail() }}" alt="{{ $group->city }}" style="object-fit: cover;" class="lazy m-auto"/>
                    </figure>
                </div>
                <div class="column">
                    <h1 class="title mb-1">{{ $group->name }}</h1>
                    <h2 class="menu-label mt-0">{{ $institute->name  }}, {{ $institute->city . ', ' . $institute->address }}</h2>

                    <hr class="mt-4"/>
                    <div class="tags">
                        @if($group->tags)
                        @foreach($group->tags as $tag)
                        <small class="tag is-rounded is-info is-light pr-2 pl-2" title="{{ $tag['tag_name'] }}" aria-label="{{ $tag['tag_name'] }}">#{{ $tag['tag_name'] }}</small>
                        @endforeach
                        @endif
                    </div>
                    <div class="content">
                        <div class="has-background-light p-4 mb-3">
                            <p>
                                <b>Közösség vezető(i):</b> {{ $group->group_leaders }}<br/>
                            </p>
                            <p>
                                <b>Alkalmak:</b> {{ $group->occasionFrequency() }}<br/>
                                <b>Korosztály:</b> {{ $group->allAgeGroupsAsString() }}<br/>
                                <b>Csatlakozási lehetőség:</b> {{ $group->joinMode() }}
                            </p>
                        </div>
                        <div>
                            {{ $group->description }}
                        </div>
                        <div class="mt-5">
                            <button class="button is-info is-outlined" onclick="$('#{{ $nvr }}').show();">Érdekel!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</com:template>