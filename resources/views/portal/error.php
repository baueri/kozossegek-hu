@extends('portal')
<div class="container text-center" style="margin:10em auto">
    <h1 class=""><span>{{ $code }} |</span> {{ $message }}</h1>
    <div><a href="{{ route('home') }}">vissza a főoldalra</a></div>
</div>