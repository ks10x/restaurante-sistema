<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pedido_itens')) {
            return;
        }

        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('prato_id')->constrained();
            $table->string('nome_prato', 120);
            $table->decimal('preco_unitario', 10, 2);
            $table->unsignedSmallInteger('quantidade')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->json('opcoes')->nullable();
            $table->text('observacao')->nullable();

            $table->index('pedido_id', 'idx_pedido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_itens');
    }
};

