<mint-extend path="layout/portal.php" :subtitle="{$subtitle ?? ''}" :body-class="{$bodyClass ?? 'inner-page'}">

    <div class="page-header">
        <div class="container">
            <div x:if="{!empty($breadcrumb)}" class="page-breadcrumb mb-2">{{ $breadcrumb }}</div>
            <h1 class="page-title">{{ $pageTitle ?? '' }}</h1>
            <p x:if="{!empty($pageSubtitle)}" class="page-subtitle">{{ $pageSubtitle }}</p>
        </div>
    </div>

    <div class="{{ $innerContainer ?? 'container' }} inner py-4 py-md-5{{ !empty($innerClass) ? ' ' . $innerClass : '' }}">
        {{ $slot }}
    </div>

</mint-extend>
