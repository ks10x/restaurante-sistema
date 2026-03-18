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
        Schema::create('prato_opcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prato_id')->constrained()->onDelete('cascade');
            $table->string('nome', 80); // "Ponto da Carne"
            $table->enum('tipo', ['radio', 'checkbox', 'select'])->default('radio');
            $table->boolean('obrigatorio')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prato_opcoes');
    }
};