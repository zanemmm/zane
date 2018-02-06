@extends('layout')

@section('main')
    @component('components.list', compact('posts'))@endcomponent
@endsection