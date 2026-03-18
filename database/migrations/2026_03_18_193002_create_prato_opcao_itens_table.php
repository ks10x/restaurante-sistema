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
        Schema::create('prato_opcao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opcao_id')->constrained('prato_opcoes')->onDelete('cascade');
            $table->string('nome', 80); // "Bem passado"
            $table->decimal('preco_adicional', 8, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prato_opcao_itens');
    }
};