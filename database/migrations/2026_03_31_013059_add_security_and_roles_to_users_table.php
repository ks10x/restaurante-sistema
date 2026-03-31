<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alterar o ENUM de role para TINYINT
        DB::statement('ALTER TABLE users MODIFY COLUMN role TINYINT NOT NULL DEFAULT 2 COMMENT "0=Admin, 1=Cozinha, 2=Cliente, 3=Entregador"');
        
        // Mover dependência do softDeletes para o final (isso não é necessário no schema normal do Laravel, mas apenas para não quebrar)
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // Campos criptografados
            $table->text('cpf_encrypted')->nullable();
            $table->text('phone_encrypted')->nullable();
            
            // Controle multi-tenant
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'cpf_encrypted',
                'phone_encrypted',
                'tenant_id'
            ]);
        });
        
        // Retornar role para enum original (opcional, rollback básico)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cozinha', 'entregador', 'cliente') NOT NULL DEFAULT 'cliente'");
    }
};
