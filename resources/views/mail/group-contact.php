@extends('mail.wrapper')

<p>Érdeklődő neve: {{ $name }}</p>
<p>Email címe: {{ $email }}</p>

<b>Üzenet:</b>
<p>
    {{ $message }}
</p>
