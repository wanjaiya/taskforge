<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    //
     protected $table = 'password_reset_tokens';

    protected $fillable = ['email', 'token'];


     const UPDATED_AT = null;

    

    public static function createResetTokenForUser($userId)
    {
        $token = bin2hex(random_bytes(16));
        

        return self::create([
            'email' => $userId,
            'token' => $token,
        ]);
    }
}
