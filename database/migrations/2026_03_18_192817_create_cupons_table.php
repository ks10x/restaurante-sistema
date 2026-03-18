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
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('descricao', 120)->nullable();
            $table->enum('tipo', ['percentual', 'fixo', 'frete_gratis']);
            $table->decimal('valor', 8, 2)->default(0);
            $table->unsignedInteger('uso_maximo')->nullable();
            $table->unsignedTinyInteger('uso_por_cliente')->default(1);
            $table->decimal('pedido_minimo', 8, 2)->nullable();
            $table->dateTime('validade_inicio')->nullable();
            $table->dateTime('validade_fim')->nullable();
            $table->boolean('ativo')->default(1);
            $table->unsignedInteger('total_uso')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};