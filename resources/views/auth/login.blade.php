@extends('layouts.base')

@section('title', 'Login')

@section('content')
<section class="one-section">
    <p>Login</p>
    <div class="card rounded-3 card-outline card-primary shadow">
        <div class="card-body login-card-body">
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    notyf.success("{{ session('success') }}");
                });
            </script>
            @endif
            @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    notyf.error("{{ $errors->first() }}");
                });
            </script>
            @endif
            <p>Enter your login information</p>
            <form id="loginForm" action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control rounded-l" id="user" name="name" placeholder="Username" aria-label="Username" aria-describedby="span-user" required autofocus>
                    <span class="input-group-text" id="span-user"><i class="fa-solid fa-user"></i></span>
                    <div class="invalid-feedback">
                        Username is required.
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" aria-label="Password" aria-describedby="span-password" required>
                    <span class="input-group-text" id="span-password"><i class="fa-solid fa-key"></i></span>
                    <div class="invalid-feedback">
                        Password is required.
                    </div>
                </div>
                <div class="g-recaptcha mb-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                <div class="invalid-feedback">Please complete the reCAPTCHA.</div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-outline-login btn-block fw-bold text-center w-100" id="btnLogin">
                            <i class="fa-solid fa-circle-arrow-right"></i> LOGIN
                        </button>
                    </div>
                </div>
                <div class="login-div2">
                    <p>Don't have an account?</p>
                    <a href="{{ route('register') }}" class="login-register">Register</a>
                </div>
            </form>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        </div>
    </div>
    <div class="footer-login mt-5">
        <p>Practica 1 | <span>{{ date('Y') }}</span></p>
    </div>
</section>

<script>
    (function() {
        'use strict';

        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    })()

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const submitButton = document.getElementById('btnLogin');

        form.addEventListener('submit', function(event) {

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fa-solid fa-circle-arrow-right"></i> LOGIN';
            }, 5000);
        });
    });
</script>

@endsection