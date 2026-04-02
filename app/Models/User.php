<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, \App\Traits\HasAudit;

    public const ROLE_ADMIN = 0;
    public const ROLE_COZINHA = 1;
    public const ROLE_CLIENTE = 2;
    public const ROLE_ENTREGADOR = 3;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'cpf',
        'cpf_encrypted',
        'phone',
        'phone_encrypted',
        'address_home',
        'address_work', 
        'avatar', 
        'role', 
        'status', 
        'last_login_at', 
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'tenant_id'
    ];
 
    protected $hidden = ['password', 'remember_token'];
 
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
        'cpf_encrypted'     => \App\Casts\EncryptedString::class,
        'phone_encrypted'   => \App\Casts\EncryptedString::class,
    ];
 
    public function isAdmin(): bool      { return (int)$this->role === self::ROLE_ADMIN; }
    public function isCozinha(): bool    { return (int)$this->role === self::ROLE_COZINHA; }
    public function isCliente(): bool    { return (int)$this->role === self::ROLE_CLIENTE; }
    public function isEntregador(): bool { return (int)$this->role === self::ROLE_ENTREGADOR; }

    public function getRoleNameAttribute(): string {
        return match((int)$this->role) {
            self::ROLE_ADMIN => 'admin',
            self::ROLE_COZINHA => 'cozinha',
            self::ROLE_ENTREGADOR => 'entregador',
            default => 'cliente',
        };
    }
 
    public function enderecos()    { return $this->hasMany(Endereco::class); }
    public function pedidos()      { return $this->hasMany(Pedido::class); }
    public function avaliacoes()   { return $this->hasMany(Avaliacao::class); }
    public function notificacoes() { return $this->hasMany(Notificacao::class); }
    public function funcionario()  { return $this->hasOne(Funcionario::class); }
 
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