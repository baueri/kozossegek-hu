@extends('portal.group.create-steps.create-wrapper')
<div class="step-container">
    <h4>Adatok ellenőrzése, regisztráció befejezése</h4>
    <p><b>Közösség neve:</b> {{ $group->name }}</p>
    <p><b>Intézmény:</b> {{ $group->institute_name }} ({{ $group->city, $group->district ? ", $group->district" : "" }})</p>
    <p><b>Korosztály:</b> {{ $group->getAgeGroups()->implode(',') }}</p>
    <p><b>Alkalmak gyakorisága:</b> {{ $group->occasionFrequency() }}</p>
    <p><b>Mely napokon:</b> {{ $group->getDays()->implode(',') }}</p>
    <p><b>Lelkiségi mozgalom:</b> {{ $group->spiritual_movement ?: '-' }}</p>
    <p><b>Közösség jellemzői:</b> {{ $selected_tags }}</p>
    <hr>
    <b>Leírás:</b>
    {{ $group->description }}
    <hr>
    <p><b>Közösségvezető(k):</b> {{ $group->group_leaders }}</p>
    <p><b>Elérhetőség (telefon):</b> {{ $group->group_leader_phone ?: '-' }}</p>
    <p><b>Elérhetőség (email):</b> {{ $group->group_leader_email }}</p>
    <hr>
    <p><b>Fotó:</b><br>
        <img src="{{ $image }}" style="width: 300px; height: auto"/>
    </p>
    <hr>
    <form action="@route('portal.my_group.create')" method="post" enctype="multipart/form-data">
        <div>
            <div class="form-group">
                <h4>Igazolás feltöltése</h4>
                @alert('info')
                <p>Nem kötelező most azonnal feltölteni, később is megteheted, de kizárólag az intézményvezető által aláírt és lepecsételt igazolással tudjuk jóváhagyni a regisztrációs kérelmet és ezáltal láthatóvá tenni a közösséget.</p>
                <p>Így tudjuk biztosítani azt, hogy a honlapunkon létező, aktív és a keresztény értékrenddel egyező közösségek legyenek.</p>
                <p>Az igazolás mintát innen tudjátok letölteni: <a href="@upload('igazolas.pdf')" download><i class="fa fa-download"></i> Igazolás minta letöltése</a></p>
                @endalert
                <p class="mb-3">
                    <small>Microsoft office dokumentum (<b>doc, docx</b>) vagy <b>pdf</b> formátum</small><br/>
                    <input type="file" name="document">
                </p>

            </div>
        </div>
        <hr>

        <div class="">
            <p>
                <label><input type="checkbox" required=""> Az <a href="">adatvédelmi tájékoztatót</a> elolvastam és elfogadom</label><br/>
                <label><input type="checkbox" required=""> A közösségem a <a href="">kereszténységgel egyező szellemiséget</a> képvisel</label>
            </p>
        </div>
        <a href="@route('portal.register_group', ['next_step' => 'group_data'])" class="btn btn-default">Adatok szerkesztése</a> <button type="submit" class="btn btn-darkblue">Közösség regisztrálása</button>
    </form>
</div>
