<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;
    protected $table = 'audit_logs';
    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    /**
     * Prevents Updates on this model
     */
    protected static function booted()
    {
        static::updating(function () {
            return false;
        });

        static::deleting(function () {
            // Se as regras LGPD pedirem, isso vira condicional, mas a base do AuditLog é imutável
            return false;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
