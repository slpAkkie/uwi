@extends('templates.minimal')

@section('title', 'View testing')

@section('body')
    <h1>Hello world!!!</h1>
    <p>It's Uwi. Page only for testing in in dev mode</p>
    @@section('there is no section')
    <p><b>{{ $version }}</b></p>
@endsection
