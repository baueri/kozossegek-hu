<hr style="margin-top: 2em; margin-bottom: 2em; border-color: #ccc; border-top: 0; border-bottom: 1px solid #ccc;">
<div style="color: #888; font-size: .9em; display: flex; margin-bottom: 2em;">
    <span>
        A <a href="{{ config('app.site_url') }}" style="color: #888;">kozossegek.hu</a> csapata
    </span>
    <span style="margin-left: auto"><a href="mailto:info@kozossegek.hu" style="color:#888;">info@kozossegek.hu</a></span>
</div>
@if($showNoReplyText)
    <p style="font-size: .9em">
        <span><b>Ez egy automatikus email, kérjük ne válaszolj erre az üzenetre.</b></span>
    </p>
@endif