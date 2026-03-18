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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 8)->unique(); // ex: "AB3K9X2M"
            $table->foreignId('user_id')->constrained();
            $table->foreignId('endereco_id')->nullable()->constrained()->onDelete('set null'); // NULL = retirada no local
            $table->enum('tipo_entrega', ['entrega', 'retirada'])->default('entrega');
            $table->enum('status', ['aguardando_pagamento', 'confirmado', 'em_producao', 'saindo_entrega', 'entregue', 'cancelado', 'reembolsado'])->default('aguardando_pagamento');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('taxa_entrega', 8, 2)->default(0);
            $table->decimal('desconto', 8, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('pagamento_metodo', ['cartao_credito', 'cartao_debito', 'pix', 'dinheiro', 'vale_alimentacao']);
            $table->enum('pagamento_status', ['pendente', 'aprovado', 'recusado', 'reembolsado'])->default('pendente');
            $table->string('pagamento_ref', 255)->nullable(); // ID da transação gateway
            $table->decimal('troco_para', 8, 2)->nullable(); // se dinheiro
            $table->text('observacoes')->nullable();
            $table->unsignedSmallInteger('tempo_estimado')->nullable(); // minutos
            $table->dateTime('confirmado_em')->nullable();
            $table->dateTime('producao_em')->nullable();
            $table->dateTime('saiu_em')->nullable();
            $table->dateTime('entregue_em')->nullable();
            $table->dateTime('cancelado_em')->nullable();
            $table->foreignId('cancelado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->text('motivo_cancelamento')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_status');
            $table->index('user_id', 'idx_user');
            $table->index('created_at', 'idx_created');
            $table->index('codigo', 'idx_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};