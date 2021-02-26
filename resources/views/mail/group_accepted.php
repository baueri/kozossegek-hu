@extends('mail.wrapper')
<b>Kedves {{ $name }}!</b>

<p>A "{{ $group_name }}" nevű közösséged jóváhagyásra került!</p>

A közösséged adatlapjának megtekintéséhez kattints az alábbi linkre:<br/>
{{ $page_link }}

