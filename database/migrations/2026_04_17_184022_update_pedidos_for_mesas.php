<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Tornando user_id opcional (para clientes nas mesas)
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Adicionando suporte às mesas
            $table->foreignId('mesa_id')->nullable()->after('user_id')->constrained('mesas')->onDelete('set null');
            $table->string('nome_cliente_avulso', 100)->nullable()->after('mesa_id');
            $table->string('tipo_consumo', 20)->default('delivery')->after('endereco_id'); // delivery, retirada, local
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['mesa_id']);
            $table->dropColumn(['mesa_id', 'nome_cliente_avulso', 'tipo_consumo']);
        });
    }
};
