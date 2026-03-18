<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 180)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->enum('role', ['admin', 'cozinha', 'entregador', 'cliente'])->default('cliente');
            $table->enum('status', ['ativo', 'inativo', 'suspenso'])->default('ativo');
            $table->rememberToken();
            $table->dateTime('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role', 'idx_role');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};