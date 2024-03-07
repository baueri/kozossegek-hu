@section('header')
    <link rel="canonical" href="@route('portal.groups')" />
    <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
@endsection
@section('footer')
    @include('asset_groups.select2')
@endsection


@extends('portal')
@featuredTitle()
    {{ $breadcrumb }}
    <h3 class="py-3 mb-0 text-center text-md-left">@lang('Közösségek')</h3>
@endfeaturedTitle

<div class="container inner">
    @yield('templom_title')
    @include('portal.partials.kozossegek_results')
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
