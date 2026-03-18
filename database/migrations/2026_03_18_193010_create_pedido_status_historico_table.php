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
        Schema::create('pedido_status_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->string('status', 30);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // quem alterou
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->index('pedido_id', 'idx_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_status_historico');
    }
};