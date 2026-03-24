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
    Schema::create('pedidos_itens', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id');
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->string('produto_nome');
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2);
        $table->decimal('subtotal', 10, 2);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('order_items');
}
};
