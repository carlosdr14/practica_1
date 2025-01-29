<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * El usuario al que se envía el correo.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param \App\Models\User $user El usuario al que se envía el correo.
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Construye el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        // Genera una URL de verificación temporalmente firmada
        $verificationUrl = URL::temporarySignedRoute(
            'verify.email',
            now()->addMinutes(60),
            ['user' => $this->user->id]
        );

        // Construye el mensaje de correo electrónico
        return $this->subject('Verify Your Email Address')
            ->view('emails.verify')
            ->with(['verificationUrl' => $verificationUrl]);
    }
}