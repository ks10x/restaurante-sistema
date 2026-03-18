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
        Schema::create('horarios_funcionamento', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('dia_semana'); // 0=Dom, 1=Seg ... 6=Sab
            $table->time('abertura');
            $table->time('fechamento');
            $table->boolean('ativo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_funcionamento');
    }
};