@extends('mail.wrapper')

<p>Érdeklődő neve: {{ $name }}</p>
<p>Email címe: {{ $email }}</p>

<b>Üzenet:</b>
<p>
    {{ $message }}
</p>
<p style="line-height: 1;">
    <small>
        <b>Információ:</b> A kapcsolatfelvevőnek az email-ben található személyes adatai nem adhatók tovább harmadik félnek.
        Az ilyen jellegű adatvédelmi visszaélés esetén a közösségvezető vállalja a felelősséget. További információkért nézd meg az <a href="/adatvedelmi-nyilatkozat">adatvédelmi irányelvet</a>.
    </small>
</p>
