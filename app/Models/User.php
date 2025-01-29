<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
        'active',
    ];

    /**
     * Los atributos que deben ocultarse para los arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
    ];

    /**
     * Genera un código de autenticación de dos factores.
     *
     * @return int El código de autenticación de dos factores generado.
     */
    public function generateTwoFactorCode()
    {
        $code = random_int(10000, 99999);

        $this->update([
            'two_factor_code' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(5),
        ]);

        return $code; 
    }

    /**
     * Verifica el código de autenticación de dos factores.
     *
     * @param int $code El código de autenticación de dos factores a verificar.
     * @return bool True si el código es válido y no ha expirado, de lo contrario False.
     */
    public function verifyTwoFactorCode($code)
    {
        return Hash::check($code, $this->two_factor_code) && now()->lt($this->two_factor_expires_at);
    }

    /**
     * Limpia el código de autenticación de dos factores.
     *
     * @return void
     */
    public function clearTwoFactorCode()
    {
        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
    }
}