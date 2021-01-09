<div style="font-size: .9rem;">
    <b>Közösség neve:</b> {{ $group->name }}<br/>
    <b>Intézmény:</b> {{ $group->institute_name }} ({{ $group->city, $group->district ? ", $group->district" : "" }})<br/>
    <b>Korosztály:</b> {{ $group->getAgeGroups()->implode(',') }}<br/>
    <b>Alkalmak gyakorisága:</b> {{ $group->occasionFrequency() }}<br/>
    <b>Mely napokon:</b> {{ $group->getDays()->implode(',') }}<br/>
    <b>Lelkiségi mozgalom:</b> {{ $group->spiritual_movement ?: '-' }}<br/>
    <b>Közösség jellemzői:</b> {{ $selected_tags }}<br/>
    <hr>
    <b>Bemutatkozás:</b>
    {{ $group->description }}
    <hr>
    <b>Közösségvezető(k):</b> {{ $group->group_leaders }}<br/>
    <b>Elérhetőség (telefon):</b> {{ $group->group_leader_phone ?: '-' }}<br/>
    <b>Elérhetőség (email):</b> {{ $group->group_leader_email }}<br/>
    @if($image)
        <hr>
        <p><b>Fotó:</b><br>
            <img src="{{ $image }}" style="width: 75px; height: auto" title="<img src='{{ $image }}' style='width:300px;'>" data-html="true" data-container='.group-register-preview' onload="$(this).tooltip()"/>
        </p>
    @endif
    <hr>
    <p class="text-right">
        <label>Az <a href="/adatvedelmi-nyilatkozat" target="_blank">adatvédelmi tájékoztatót</a> elolvastam és elfogadom <input type="checkbox" required id="adatvedelmi-tajekoztato"></label><br/>
        <label>A közösségem a <a href="/iranyelveink" target="_blank">kereszténységgel egyező szellemiséget</a> képvisel <input type="checkbox" required id="iranyelvek"></label>
    </p>
</div>
<style>
    .group-register-preview .tooltip { opacity:1!important; }
</style>
