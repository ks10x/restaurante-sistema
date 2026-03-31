<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;
    protected $table = 'login_attempts';
    protected $guarded = [];

    protected $casts = [
        'success' => 'boolean',
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::updating(function () {
            // Imutável
            return false;
        });

        static::deleting(function () {
            // Imutável
            return false;
        });
    }
}
