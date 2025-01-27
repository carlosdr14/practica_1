@extends('layouts.base')

@section('title', 'Authentication')

@section('content')
<section class="authentication-section">
    <p>Enter your authentication code</p>
    <div class="auth-div">
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
        <form action="{{ route('verify.action') }}" method="POST" id="authForm">
            @csrf
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth1" name="auth1" maxlength="1" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth2" name="auth2" maxlength="1" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth3" name="auth3" maxlength="1" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth4" name="auth4" maxlength="1" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth5" name="auth5" maxlength="1" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="auth-div3">
                <button type="submit" form="authForm" class="btn btn-auth">Authenticate account</button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.auth-form-control');

        inputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                // Solo permitir valores numéricos
                this.value = this.value.replace(/[^0-9]/g, '');

                // Mover el cursor al siguiente input si se ingresa un carácter
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(event) {
                // Mover el cursor al input anterior si se presiona la tecla de retroceso
                if (event.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    });
</script>
@endsection