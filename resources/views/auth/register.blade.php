@extends('layouts.base')

@section('title', 'Register')

@section('content')
<section class="one-section">
    <p>Register</p>
    <div class="card rounded-3 card-outline card-primary shadow">
        <div class="card-body register-card-body">
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    notyf.success("{{ session('success') }}");
                    setTimeout(() => window.location.href = "{{ route('login') }}", 1500);
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
            <p>Fill in the details to create an account</p>
            <form id="registerForm" action="{{ route('register.action') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control rounded-l" id="username" name="name" placeholder="Username" required minlength="6" maxlength="30">
                        <span class="input-group-text" id="span-user"><i class="fa-solid fa-user"></i></span>
                        <div class="invalid-feedback">
                            Username must be between 6 and 30 characters.
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                <span class="input-group-text" id="span-email"><i class="fa-solid fa-envelope"></i></span>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8" maxlength="16" pattern="(?=.*[A-Z])(?=.*[@$!%*#?&]).{8,16}">
                                <span class="input-group-text" id="span-password"><i class="fa-solid fa-key"></i></span>
                                <div class="invalid-feedback">
                                    Password must be between 8 and 16 characters and include at least one uppercase letter, one number, and one special character.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" id="btn-register" class="btn btn-outline-login btn-block fw-bold w-100">
                    <i class="fa-solid fa-circle-arrow-right"></i> REGISTER
                </button>
                <div class="login-div2">
                    <p>If you already have an account,</p>
                    <a href="{{ route('login') }}" class="login-register">Login</a>
                </div>
            </form>
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
    })();

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const submitButton = document.getElementById('btn-register');

        form.addEventListener('submit', function(event) {

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fa-solid fa-circle-arrow-right"></i> REGISTER';
            }, 5000);
        });
    });
</script>

@endsection