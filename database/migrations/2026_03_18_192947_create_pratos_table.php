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
        Schema::create('pratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained();
            $table->string('nome', 120);
            $table->text('descricao');
            $table->decimal('preco', 10, 2);
            $table->decimal('preco_promocional', 10, 2)->nullable();
            $table->string('imagem', 255)->nullable();
            $table->json('imagens_extras')->nullable(); // array de URLs
            $table->unsignedSmallInteger('calorias')->nullable();
            $table->unsignedSmallInteger('tempo_preparo')->nullable(); // minutos
            $table->string('porcao', 60)->nullable(); // "Serve 2 pessoas"
            $table->boolean('disponivel')->default(1);
            $table->boolean('destaque')->default(0);
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->decimal('avaliacao_media', 3, 2)->default(0.00);
            $table->unsignedInteger('total_avaliacoes')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('categoria_id', 'idx_categoria');
            $table->index('disponivel', 'idx_disponivel');
            $table->index('destaque', 'idx_destaque');
            $table->fullText(['nome', 'descricao'], 'idx_ft_busca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pratos');
    }
};