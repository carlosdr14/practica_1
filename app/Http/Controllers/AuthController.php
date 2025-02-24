<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCode;
use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Helpers\SlackHelper;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica el middleware de reCAPTCHA a los métodos de registro e inicio de sesión.
     */
    public function __construct()
    {
        $this->middleware('recaptcha')->only(['register', 'login']);
    }

    /**
     * Maneja el registro de un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Mensajes de validación personalizados
        $messages = [
            'name.unique' => 'User or Email already exists.',
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 6 characters.',
            'name.max' => 'Name must be at most 30 characters.',
            'name.string' => 'Name must be a string.',
            'email.unique' => 'User or Email already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must be at most 16 characters.',
            'password.string' => 'Password must be a string.',
            'password.regex' => 'Password must be between 8 and 16 characters and include at least one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];

        Log::info('Registering new user');

        // dd($request);
        // Validar la solicitud
        $request->validate([
            'name' => 'required|string|max:30|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:16|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/|confirmed',
        ], $messages);

        // if ($validar->fails()) {
        //     Log::info('Creating new user');
        // }
    

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Log::info('User created');

        // Enviar correo de verificación
        Mail::to($user->email)->send(new EmailVerification($user));

        // Enviar notificación a Slack
        SlackHelper::sendMessage("🆕 Nuevo usuario registrado: {$user->name} ({$user->email})");

        return redirect()->route('register')->with('success', 'Account successfully created, an email has been sent to verify your email address.');
    }

    /**
     * Verifica el correo electrónico del usuario.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            // Enviar notificación a Slack
            SlackHelper::sendMessage("❌ Error: Usuario no encontrado al intentar verificar el correo electrónico. ID de usuario: {$userId}");
            return redirect()->route('login')->withErrors(['error' => 'User not found.']);
        }

        if (!URL::hasValidSignature(request())) {
            // Enviar notificación a Slack
            SlackHelper::sendMessage("❌ Error: Enlace de verificación de correo electrónico inválido o expirado para el usuario: {$user->name} ({$user->email})");
            return redirect()->route('login')->withErrors(['error' => 'Invalid or expired verification link.']);
        }

        $user->active = 1;
        $user->save();

        // Enviar notificación a Slack
        SlackHelper::sendMessage("✅ Correo electrónico verificado para el usuario: {$user->name} ({$user->email})");

        return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in.');
    }

    /**
     * Maneja el inicio de sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Mensajes de validación personalizados
        $messages = [
            'name.required' => 'Username is required.',
            'name.min' => 'Username must be at least 6 characters.',
            'name.max' => 'Username must be at most 30 characters.',
            'name.string' => 'Username must be a string.',
            'password.required' => 'Password is required.',
        ];

        // Validar la solicitud
        $request->validate([
            'name' => 'required|string|min:6|max:30',
            'password' => 'required',
        ], $messages);

        $user = User::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->active) {
                // Enviar notificación a Slack
                SlackHelper::sendMessage("⚠️ Intento de inicio de sesión en cuenta inactiva: {$user->name} ({$user->email})");
                return back()->withErrors(['error' => 'Your account is not active. Please verify your email or contact support.']);
            }

            // Enviar notificación a Slack
            SlackHelper::sendMessage("🔑 Usuario inició sesión: {$user->name} ({$user->email})");
            $code = rand(10000, 99999);

            $user->update([
                'two_factor_code' => Hash::make($code),
                'two_factor_expires_at' => now()->addMinutes(5),
            ]);

            Mail::to($user->email)->send(new TwoFactorCode($code, $user));

            session(['auth_user_id' => $user->id]);

            return redirect()->route('authentication');
        }

        // Enviar notificación a Slack
        SlackHelper::sendMessage("❌ Intento de inicio de sesión fallido para el nombre de usuario: {$request->name}");

        return back()->withErrors(['error' => 'Invalid username or password.']);
    }

    /**
     * Verifica el código de autenticación de dos factores.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyTwoFactor(Request $request)
    {
        $userId = session('auth_user_id');
        $user = User::find($userId);

        if (!$user) {
            // Enviar notificación a Slack
            SlackHelper::sendMessage("❌ Error: No se encontró la sesión del usuario al intentar verificar el código de autenticación de dos factores. ID de usuario: {$userId}");
            return redirect()->route('login')->withErrors(['error' => 'No user session found.']);
        }

        // Mensajes de validación personalizados
        $messages = [
            'auth1.required' => 'The first authentication code is required.',
            'auth1.numeric' => 'The first authentication code must be a number.',
            'auth2.required' => 'The second authentication code is required.',
            'auth2.numeric' => 'The second authentication code must be a number.',
            'auth3.required' => 'The third authentication code is required.',
            'auth3.numeric' => 'The third authentication code must be a number.',
            'auth4.required' => 'The fourth authentication code is required.',
            'auth4.numeric' => 'The fourth authentication code must be a number.',
            'auth5.required' => 'The fifth authentication code is required.',
            'auth5.numeric' => 'The fifth authentication code must be a number.',
        ];

        // Validar la solicitud
        $request->validate([
            'auth1' => 'required|numeric',
            'auth2' => 'required|numeric',
            'auth3' => 'required|numeric',
            'auth4' => 'required|numeric',
            'auth5' => 'required|numeric',
        ], $messages);

        $code = implode('', [
            $request->auth1,
            $request->auth2,
            $request->auth3,
            $request->auth4,
            $request->auth5,
        ]);

        if (Hash::check($code, $user->two_factor_code) && $user->two_factor_expires_at->isFuture()) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            // Mensaje de éxito en la verificación
            session()->flash('success', 'Authentication code verified successfully.');

            $token = bin2hex(random_bytes(32));
            $user->update(['two_factor_token' => $token]);

            // Loguear al usuario
            Auth::login($user);

            session()->forget('auth_user_id');

            // Enviar notificación a Slack
            SlackHelper::sendMessage("✅ Código de autenticación de dos factores verificado para el usuario: {$user->name} ({$user->email})");

            return redirect()->route('home');
        }

        // Enviar notificación a Slack
        SlackHelper::sendMessage("❌ Error: Código de autenticación de dos factores incorrecto o expirado para el usuario: {$user->name} ({$user->email})");

        return back()->withErrors(['two_factor_code' => 'The authentication code is incorrect or has expired.']);
    }
}
