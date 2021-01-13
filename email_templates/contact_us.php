@extends('mail.wrapper')
<p><b>Új üzenet érkezett a kozossegek.hu oldalról</b></p>

<p>
    <b>Név:</b> {{ $name }}<br/>
    <b>email cím:</b> {{ $email }}</p>
</p>
<b>Üzenet:</b><br/>
{{ str_replace(PHP_EOL, "<br/>", $message) }}
