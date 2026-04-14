<?php
/** @var \App\Models\ChurchGroupView $group */
$cityLine = $group->city;
if (!empty($group->district)) {
    $cityLine .= ', ' . $group->district;
}
?>
<div style="font-size: .9rem;" id="group-preview">
    <table style="width: 100%;">
        <tr><th>Közösség neve</th><td><?= htmlspecialchars((string) $group->name, ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Intézmény</th><td><?= htmlspecialchars((string) $group->institute_name, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($cityLine, ENT_QUOTES, 'UTF-8') ?>)</td></tr>
        <tr><th>Korosztály</th><td><?= htmlspecialchars($group->allAgeGroupsAsString(), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Alkalmak gyakorisága</th><td><?= htmlspecialchars($group->occasionFrequency(), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Mely napokon</th><td><?= htmlspecialchars($group->getDaysAsString(), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Lelkiségi mozgalom</th><td><?= htmlspecialchars((string) ($group->spiritual_movement ?: '-'), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Csatlakozás módja</th><td><?= htmlspecialchars((string) ($group->joinModeText() ?: '-'), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Közösség jellemzői</th><td><?= htmlspecialchars((string) ($selected_tags ?? ''), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><th>Bemutatkozás</th><td><?= $group->description ?></td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><th>Közösségvezető(k)</th><td><?= htmlspecialchars((string) $group->group_leaders, ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Elérhetőség (telefon)</th><td><?= htmlspecialchars((string) ($phone_number ?: '-'), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Elérhetőség (email)</th><td><?= htmlspecialchars((string) ($email ?? ''), ENT_QUOTES, 'UTF-8') ?></td></tr>
    </table>
    @if(!empty($image))
        <hr>
        <p><b>Fotó:</b><br>
            <img src="{{ $image }}" style="width: 75px; height: auto" title="<img src='{{ $image }}' style='width:300px;'>" data-html="true" data-container=".group-register-preview" onload="$(this).tooltip()"/>
        </p>
    @endif
    <hr>
    <p class="text-start">
        <?php echo (new \App\Http\Components\AszfCheckBox())->render(); ?><br>
        <label><input type="checkbox" required id="iranyelvek"> A közösségem a <a href="/iranyelveink" target="_blank"><b><u>kereszténységgel egyező szellemiséget</u></b></a> képvisel</label>
    </p>
</div>
<style>
    .group-register-preview .tooltip { opacity:1!important; }
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
