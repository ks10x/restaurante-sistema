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
        Schema::create('estoque_movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained();
            $table->foreignId('user_id')->constrained(); // quem realizou
            $table->enum('tipo', ['entrada', 'saida', 'ajuste', 'perda']);
            $table->decimal('quantidade', 10, 3);
            $table->decimal('quantidade_anterior', 10, 3);
            $table->decimal('quantidade_posterior', 10, 3);
            $table->decimal('preco_unitario', 10, 4)->nullable();
            $table->string('motivo', 255)->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable(); // ID do pedido se for consumo
            $table->string('referencia_tipo', 50)->nullable(); // 'pedido', 'compra', 'ajuste'
            $table->timestamps();

            $table->index('insumo_id', 'idx_insumo');
            $table->index('created_at', 'idx_data');
            $table->index('tipo', 'idx_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque_movimentacoes');
    }
};