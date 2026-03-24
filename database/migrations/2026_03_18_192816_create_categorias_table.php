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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60);
            $table->string('slug', 70)->unique();
            $table->string('descricao')->nullable(); // Faltava este
            $table->string('icone')->nullable();
            $table->string('cor', 7)->nullable();    // Faltava este
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->boolean('ativa')->default(1);    // Usando 'ativa' como no seu Model
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};