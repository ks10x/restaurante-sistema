<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
    'name',
    'email',
    'password',
    'cpf',
    'phone', 
    'avatar', 
    'role', 
    'status', 
    'last_login_at', 
];
 
    protected $hidden = ['password', 'remember_token'];
 
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
    ];
 
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isCozinha(): bool    { return $this->role === 'cozinha'; }
    public function isCliente(): bool    { return $this->role === 'cliente'; }
    public function isEntregador(): bool { return $this->role === 'entregador'; }
 
    public function enderecos()    { return $this->hasMany(Endereco::class); }
    public function pedidos()      { return $this->hasMany(Pedido::class); }
    public function avaliacoes()   { return $this->hasMany(Avaliacao::class); }
    public function notificacoes() { return $this->hasMany(Notificacao::class); }
 
    public function enderecoAtivo() {
        return $this->enderecos()->where('principal', 1)->first()
            ?? $this->enderecos()->latest()->first();
    }
 
    public function getAvatarUrlAttribute(): string {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=D85A30&color=fff';
    }
}