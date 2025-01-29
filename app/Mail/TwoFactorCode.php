<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TwoFactorCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * El código de autenticación de dos factores.
     *
     * @var int
     */
    public $code;

    /**
     * El usuario al que se envía el correo.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param int $code El código de autenticación de dos factores.
     * @param \App\Models\User $user El usuario al que se envía el correo.
     * @return void
     */
    public function __construct($code, User $user)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Construye el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Two Factor Authentication Code')
            ->view('emails.two_factor_code')
            ->with([
                'code' => $this->code,
                'user' => $this->user,
            ]);
    }
}