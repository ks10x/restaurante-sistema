<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PedidoPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_COZINHA]);
    }

    public function view(User $user, Pedido $pedido): bool
    {
        return $user->id === $pedido->user_id || in_array($user->role, [User::ROLE_ADMIN, User::ROLE_COZINHA]);
    }

    public function create(User $user): bool
    {
        return $user->role === User::ROLE_CLIENTE || $user->role === User::ROLE_ADMIN;
    }

    public function update(User $user, Pedido $pedido): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_COZINHA]);
    }

    public function delete(User $user, Pedido $pedido): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }
}
