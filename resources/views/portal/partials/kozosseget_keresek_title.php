<div class="featured">
    <div class="container justify-content-center text-">
        <div class="featured-content">
            <h1 class='h2' style="padding-bottom: 0">Közösséget keresek</h1>
            <ul class="age-group-select text-shadowed">
                <li @if($selected_age_group === 'tinedzser') class="active" @endif><a href="/kozossegek/tinedzser?{{ $age_group_query }}">tinédzser</a></li>
                <li @if($selected_age_group === 'fiatal_felnott') class="active" @endif><a href="/kozossegek/fiatal_felnott?{{ $age_group_query }}">fiatal felnőtt</a></li>
                <li @if($selected_age_group === 'kozepkoru') class="active" @endif><a href="/kozossegek/kozepkoru?{{ $age_group_query }}">középkorú</a></li>
                <li @if($selected_age_group === 'nyugdijas') class="active" @endif><a href="/kozossegek/nyugdijas?{{ $age_group_query }}">nyugdíjas</a></li>
            </ul>
        </div>
    </div>
    <div style="opacity: 1.0;" class="featured-overlay"></div>
</div>