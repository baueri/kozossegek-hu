@extends('mail.wrapper')
<h1>{{ $exception->getMessage() }}</h1>
<pre>
    {{ $exception->getTraceAsString() }}
</pre>
