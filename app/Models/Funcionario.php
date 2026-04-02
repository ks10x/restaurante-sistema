<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $fillable = [
        'user_id',
        'codigo_identificacao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
