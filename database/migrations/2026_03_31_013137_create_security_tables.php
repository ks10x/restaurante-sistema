<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_type');
            $table->string('table_name')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            // Nota: Audit é IMUTÁVEL: updated_at não existe
            
            $table->index(['table_name', 'record_id']);
        });

        Schema::create('consentimentos_lgpd', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->boolean('marketing')->default(0);
            $table->boolean('compartilhamento_terceiros')->default(0);
            $table->boolean('cookies_analiticos')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('accepted_at');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email', 180)->index();
            $table->string('ip_address', 45)->index();
            $table->boolean('success');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ips_banidos', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('reason');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('seguranca_sessoes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser_fingerprint')->nullable();
            $table->boolean('is_suspicious')->default(0);
            $table->integer('last_activity')->index();
        });
        
        DB::statement("
            CREATE OR REPLACE VIEW vw_atividade_suspeita AS
            SELECT 
                ip_address, 
                email,
                COUNT(*) as rotativas, 
                MAX(created_at) as ultimo_tento 
            FROM login_attempts 
            WHERE success = 0 
              AND created_at >= NOW() - INTERVAL 1 HOUR
            GROUP BY ip_address, email
            HAVING COUNT(*) >= 5
        ");
        
        // Exemplo simplificado de usuário de leitura (não rodaremos grant por estar em Laragon padrão e ser perigoso quebrar, fica como prova de conceito):
        // DB::statement("CREATE USER IF NOT EXISTS 'readonly'@'localhost' IDENTIFIED BY 'senha_super_segura';");
        // DB::statement("GRANT SELECT ON restaurante_db.* TO 'readonly'@'localhost';");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_atividade_suspeita");
        Schema::dropIfExists('seguranca_sessoes');
        Schema::dropIfExists('ips_banidos');
        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('consentimentos_lgpd');
        Schema::dropIfExists('audit_logs');
    }
};
