@extends('layout')

@section('main')
    @component('components.error', compact('status', 'message'))@endcomponent
@endsection