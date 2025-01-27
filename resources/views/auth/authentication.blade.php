@extends('layouts.base')

@section('title', 'Authentication')

<section class="authentication-section">
    <p>Enter your authentication code</p>
    <div class="auth-div">
        <form action="{{ route('verify.action') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth1" name="auth1" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth2" name="auth2" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth3" name="auth3" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth4" name="auth4" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="auth-div2">
                        <input type="text" class="auth-form-control rounded-l" id="auth5" name="auth5" placeholder="0" aria-label="0">
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </form>
    </div>
    <div class="auth-div3">
        <button class="btn btn-auth">Authenticate account</button>
    </div>
</section>

@section('content')

@endsection