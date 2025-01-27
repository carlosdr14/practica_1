<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
    ];

    public function generateTwoFactorCode()
    {
        $code = random_int(10000, 99999);

        $this->update([
            'two_factor_code' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(5),
        ]);

        return $code; 
    }

    public function verifyTwoFactorCode($code)
    {
        return Hash::check($code, $this->two_factor_code) && now()->lt($this->two_factor_expires_at);
    }

    public function clearTwoFactorCode()
    {
        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
    }
}
