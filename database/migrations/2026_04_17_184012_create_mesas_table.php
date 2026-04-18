<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 10)->unique();
            $table->integer('capacidade')->default(4);
            $table->enum('status', ['livre', 'ocupada', 'chamando', 'aguardando_pagamento'])->default('livre');
            $table->string('token_hash', 32)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
