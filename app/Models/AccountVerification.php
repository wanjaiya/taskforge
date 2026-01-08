<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountVerification extends Model
{
    //
    protected $fillable = ['user_id', 'token', 'expires_at'];


    public static function createTokenForUser($userId)
    {
        $token = bin2hex(random_bytes(16));
        $expiresAt = now()->addHours(24);

        return self::create([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }
}
