@section('portal2.header')
<link rel="canonical" href="@route('portal.groups')" />
<meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
@endsection
<com:section name="portal2.footer_scripts">
    <script>
        $(() => {
            $(".group-tag").change(function () {
                let val = "";
                $(".group-tag:checked").each(function () {
                    val += (val ? "," : "") + $(this).val();
                });

                $("[name=tags]").val(val);
            });

        });
    </script>
</com:section>
@extends('portal2.main')
<section class="section section-with-bg is-bold pt-6 pb-6">
    <div class="container is-max-desktop pt-6 pb-6">
        <h1 class="title has-text-white">Közösség keresése</h1>
        <form method="get" class="mt-4">
            <div class="field has-addons">
                <div class="control"><input class="input is-rounded" type="text" placeholder="Keresés" name="search"></div>
                <div class="control">
                    <div class="select is-rounded">
                        <select name="korosztaly">
                            <option value="">-- korosztály --</option>
                            <option value="tinedzser">tinédzser</option>
                            <option value="fiatal_felnott">fiatal felnőtt</option>
                            <option value="kozepkoru">középkorú</option>
                            <option value="nyugdijas">nyugdíjas</option>
                        </select>
                    </div>
                </div>
                <div class="control">
                    <button type="submit" class="button is-info is-rounded">
                        <span class="icon is-small"><i class="fa fa-search"></i></span>
                        <span>Keresés</span>
                    </button>
                </div>
            </div>
            <div>
                <span x:foreach="$tags as $i => $tag">
                    <input type="checkbox"
                           class="group-tag"
                           id="tag-{{ $tag['slug'] }}"
                           value="{{ $tag['slug'] }}"
                           style="display: none;"
                           @checked(in_array($tag['slug'], $selected_tags))>
                    <label for="tag-{{ $tag['slug'] }}" class="mr-1 tag is-rounded is-light group-tag-badge mb-1" aria-label="{{ $tag['tag'] }}">
                        <com:icon class="hashtag mr-1"/> {{ $tag['tag'] }}
                    </label>
                </span>
                <input type="hidden" name="tags" value="{{ $filter['tags'] }}">


            </div>
        </form>
    </div>
</section>
<section id="grop-list" class="section">
    <div class="container">
        @if($total)
            @include('portal2.partial.kozossegek_lista')
        <div class="mt-5 pt-5">
            @include('portal2.partial.simple-pager', ['route' => 'portal.groups.page','total' => $total,'page' => $page,'perpage' => $perpage,'routeparams' => $filter])
        </div>
        @else
            <h5 class="has-text-center"><i>Sajnos nem találtunk ilyen közösséget.</i></h5>
            <h6 class="has-text-center"><i>Próbáld meg más keresési feltételekkel.</i></h6>
        @endif
    </div>
</section>