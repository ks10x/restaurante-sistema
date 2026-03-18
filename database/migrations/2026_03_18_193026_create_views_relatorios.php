<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // View Vendas Diárias
        DB::statement("
            CREATE OR REPLACE VIEW vw_vendas_diarias AS
            SELECT
                DATE(p.created_at)          AS data,
                COUNT(*)                    AS total_pedidos,
                SUM(p.total)                AS faturamento,
                AVG(p.total)                AS ticket_medio,
                SUM(CASE WHEN p.status = 'entregue' THEN 1 ELSE 0 END) AS entregues,
                SUM(CASE WHEN p.status = 'cancelado' THEN 1 ELSE 0 END) AS cancelados
            FROM pedidos p
            WHERE p.status NOT IN ('aguardando_pagamento')
            GROUP BY DATE(p.created_at)
        ");

        // View Pratos Populares
        DB::statement("
            CREATE OR REPLACE VIEW vw_pratos_populares AS
            SELECT
                pr.id,
                pr.nome,
                c.nome AS categoria,
                SUM(pi.quantidade)          AS total_vendido,
                SUM(pi.subtotal)            AS receita_gerada,
                pr.avaliacao_media
            FROM pedido_itens pi
            JOIN pratos pr ON pi.prato_id = pr.id
            JOIN categorias c ON pr.categoria_id = c.id
            JOIN pedidos p ON pi.pedido_id = p.id
            WHERE p.status = 'entregue'
            GROUP BY pr.id, pr.nome, c.nome, pr.avaliacao_media
            ORDER BY total_vendido DESC
        ");

        // View Estoque Crítico
        DB::statement("
            CREATE OR REPLACE VIEW vw_estoque_critico AS
            SELECT
                i.id,
                i.nome,
                i.unidade,
                i.quantidade_atual,
                i.quantidade_minima,
                ROUND((i.quantidade_atual / NULLIF(i.quantidade_minima, 0)) * 100, 1) AS percentual,
                CASE
                    WHEN i.quantidade_atual = 0 THEN 'zerado'
                    WHEN i.quantidade_atual <= i.quantidade_minima THEN 'critico'
                    WHEN i.quantidade_atual <= i.quantidade_minima * 1.5 THEN 'atencao'
                    ELSE 'ok'
                END AS nivel
            FROM insumos i
            WHERE i.ativo = 1
            ORDER BY percentual ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_vendas_diarias");
        DB::statement("DROP VIEW IF EXISTS vw_pratos_populares");
        DB::statement("DROP VIEW IF EXISTS vw_estoque_critico");
    }
};