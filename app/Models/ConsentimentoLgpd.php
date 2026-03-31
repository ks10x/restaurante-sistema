<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentimentoLgpd extends Model
{
    protected $table = 'consentimentos_lgpd';
    protected $guarded = [];

    protected $casts = [
        'marketing' => 'boolean',
        'compartilhamento_terceiros' => 'boolean',
        'cookies_analiticos' => 'boolean',
        'accepted_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
