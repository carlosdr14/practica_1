<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TwoFactorCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $user;

    // Constructor
    public function __construct($code, User $user)
    {
        $this->code = $code;
        $this->user = $user;
    }

    // Construcción del correo
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