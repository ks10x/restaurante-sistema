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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo', 60);
            $table->string('titulo', 120);
            $table->text('mensagem');
            $table->json('dados')->nullable();
            $table->boolean('lida')->default(0);
            $table->dateTime('lida_em')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'lida'], 'idx_user_lida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};