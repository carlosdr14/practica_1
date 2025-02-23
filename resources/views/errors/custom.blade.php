@extends('layouts.base')

@section('title', 'Error - {{ $code }}')

@section('content')
<h1>Error: {{ $message }}</h1>
<p>Code: {{ $code }}</p>

@if (!empty($details))
    <h3>Details:</h3>
    <ul>
        @foreach ($details as $field => $error)
            <li>{{ $field }}: {{ implode(', ', $error) }}</li>
        @endforeach
    </ul>
@endif

<a href="{{ route('home') }}">Go to Home</a>
@endsection