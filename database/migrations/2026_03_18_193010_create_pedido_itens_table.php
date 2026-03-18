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
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('prato_id')->constrained();
            $table->string('nome_prato', 120); // snapshot do nome
            $table->decimal('preco_unitario', 10, 2); // snapshot do preço
            $table->unsignedSmallInteger('quantidade')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->json('opcoes')->nullable(); // [{nome: "Ponto", valor: "Bem passado", adicional: 0}]
            $table->text('observacao')->nullable();

            $table->index('pedido_id', 'idx_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_itens');
    }
};