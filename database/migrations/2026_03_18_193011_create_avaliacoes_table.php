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
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('pedido_id')->constrained();
            $table->foreignId('prato_id')->nullable()->constrained()->onDelete('set null'); // NULL = avaliação geral
            $table->unsignedTinyInteger('nota'); // 1 a 5
            $table->text('comentario')->nullable();
            $table->text('resposta_admin')->nullable();
            $table->boolean('aprovado')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'pedido_id', 'prato_id'], 'uq_user_pedido_prato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};