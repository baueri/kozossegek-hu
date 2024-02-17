@section('header')
    <link rel="canonical" href="@route('portal.groups')" />
    <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
@endsection
@section('footer')
    @include('asset_groups.select2')
@endsection

@section('header_content')
    @include('portal.partials.kozosseget_keresek_title')
@endsection

@extends('portal')
    <div class="container inner">
        {{ $breadcrumb }}
        @yield('templom_title')
        @include('portal.partials.kozossegek-view2')
        @include('partials.simple-pager', ['route' => 'portal.groups.page','total' => $total,'page' => $page,'perpage' => $perpage,'routeparams' => $filter])
    </div>
<script>
$(() => {

    $(".group-tag").change(function () {
        var val = "";
        $(".group-tag:checked").each(function () {
            val += (val ? "," : "") + $(this).val();
        });

        $("[name=tags]").val(val);
    });
});
</script>
