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
        Schema::create('prato_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prato_id')->constrained()->onDelete('cascade');
            $table->string('nome', 100);
            $table->string('quantidade', 60)->nullable(); // "100g", "2 colheres"
            $table->boolean('alergenico')->default(0);
            $table->enum('tipo_alergenico', ['gluten', 'lactose', 'amendoim', 'frutos_do_mar', 'ovo', 'soja', 'nozes', 'outro'])->nullable();

            $table->index('prato_id', 'idx_prato_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prato_ingredientes');
    }
};