<!-- Gespeichert in resources/views/child.blade.php -->

@extends('layouts.app') {{-- Erweitert die app.blade.php --}}


@section('sidebar')
@parent

<p>This is appended to the master sidebar.</p>
@endsection



{{-- An dieser Stelle definieren wir den Inhalt, der in der app.blade.php an der Stelle @yield('content') eingesetzt wird. --}}

@section('content')
<p>Dieser Inhalt wird eingesetzt</p>
@endsection