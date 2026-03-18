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
        Schema::create('cupom_usos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cupom_id')->constrained('cupons');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->decimal('desconto_aplicado', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupom_usos');
    }
};