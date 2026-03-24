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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('id_unico')->unique(); // Ex: PED-2026-X82
        
        // Status do sistema de acompanhamento
        $table->enum('status', ['pedido enviado', 'pedido em preparo', 'saindo para entrega', 'entregue', 'cancelado'])
              ->default('pedido enviado');
        
        $table->decimal('valor_total', 10, 2);
        $table->string('metodo_pagamento'); // pix, credit_card
        $table->string('pagamento_id')->nullable(); // ID retornado pelo Pagar.me
        
        // Informações de entrega no momento da compra (snapshot)
        $table->text('endereco_entrega');
        $table->string('cidade')->default('São Paulo');
        $table->string('bairro')->default('Guaianases');
        
        $table->timestamps();
    });

    // Tabela para os itens do pedido (Relacionamento 1:N)
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->string('produto_nome');
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
