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
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 120);
            $table->text('descricao')->nullable();
            $table->enum('unidade', ['kg', 'g', 'l', 'ml', 'un', 'cx', 'pct']);
            $table->decimal('quantidade_atual', 10, 3)->default(0);
            $table->decimal('quantidade_minima', 10, 3)->default(0); // alerta de nível baixo
            $table->decimal('quantidade_maxima', 10, 3)->nullable();
            $table->decimal('preco_unitario', 10, 4)->default(0);
            $table->string('fornecedor', 120)->nullable();
            $table->string('categoria', 60)->nullable();
            $table->string('codigo_barras', 50)->nullable();
            $table->boolean('ativo')->default(1);
            $table->timestamps();

            $table->index('nome', 'idx_nome');
            $table->index(['quantidade_atual', 'quantidade_minima'], 'idx_nivel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};