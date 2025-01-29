<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = config('services.recaptcha.secret_key');

        if (!$recaptchaResponse) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA is required.']);
        }

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $responseData = $verify->json();

        if (!$responseData['success']) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed.']);
        }

        return $next($request);
    }
}
