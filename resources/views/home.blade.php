@extends('layouts.base')

@section('title', 'Home')

@section('content')
<section class="home-section">
    <div>
        <h1>Home</h1>
        <p>Welcome to our website!</p>

        <!-- Formulario de logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</section>
@endsection