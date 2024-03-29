<div style="font-size: .9rem;" id="group-preview">
    <table style="width: 100%;">
        <tr><th>Közösség neve</th><td>{{ $group->name }}</td></tr>
        <tr><th>Intézmény</th><td> {{ $group->institute_name }} ({{ $group->city, $group->district ? ", $group->district" : "" }})</td></tr>
        <tr><th>Korosztály</th><td>{{ $group->allAgeGroupsAsString() }}</td></tr>
        <tr><th>Alkalmak gyakorisága</th><td>{{ $group->occasionFrequency() }}<br/></td></tr>
        <tr><th>Mely napokon</th><td> {{ $group->getDaysAsString() }}<br/></td></tr>
        <tr><th>Lelkiségi mozgalom</th><td> {{ $group->spiritual_movement ?: '-' }}<br/></td></tr>
        <tr><th>Csatlakozás módja</th><td> {{ $group->joinModeText() ?: '-' }}<br/></td></tr>
        <tr><th>Közösség jellemzői</th><td> {{ $selected_tags }}</td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><th>Bemutatkozás</th><td>{{ $group->description }}</td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><th>Közösségvezető(k)</th><td> {{ $group->group_leaders }}</td></tr>
        <tr><th>Elérhetőség (telefon)</th><td> {{ $phone_number ?: '-' }}</td></tr>
        <tr><th>Elérhetőség (email)</th><td> {{ $email ?? '' }}</td></tr>
    </table>
    @if($image)
        <hr>
        <p><b>Fotó:</b><br>
            <img src="{{ $image }}" style="width: 75px; height: auto" title="<img src='{{ $image }}' style='width:300px;'>" data-html="true" data-container='.group-register-preview' onload="$(this).tooltip()"/>
        </p>
    @endif
    <hr>
    <p class="text-left">
        @component('aszf')<br/>
        <label><input type="checkbox" required id="iranyelvek"> A közösségem a <a href="/iranyelveink" target="_blank"><b><u>kereszténységgel egyező szellemiséget</u></b></a> képvisel</label>
    </p>
</div>
<style>
    .group-register-preview .tooltip { opacity:1!important; }
    #group-preview {

    }
    #group-preview th {
        text-align: right;
        font-weight: bold;
        padding-right: 15px;
        width: 1px;
        white-space: nowrap;
    }
    #group-preview th, #group-preview td {
        vertical-align: top;
        padding-bottom: 10px;
    }
</style>
