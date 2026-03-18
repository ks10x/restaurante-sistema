<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RestauranteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuário Admin padrão (senha: admin@123)
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@restaurante.com',
            'password' => Hash::make('admin@123'), // Usando Hash::make para gerar o bcrypt
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Cozinha Principal',
            'email' => 'cozinha@restaurante.com',
            'password' => Hash::make('admin@123'), // Usando Hash::make para gerar o bcrypt
            'role' => 'cozinha',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Categorias iniciais
        $categorias = [
            ['nome' => 'Entradas', 'icone' => '🥗', 'ordem' => 1, 'ativo' => 1],
            ['nome' => 'Pratos Principais', 'icone' => '🍽️', 'ordem' => 2, 'ativo' => 1],
            ['nome' => 'Massas', 'icone' => '🍝', 'ordem' => 3, 'ativo' => 1],
            ['nome' => 'Grelhados', 'icone' => '🥩', 'ordem' => 4, 'ativo' => 1],
            ['nome' => 'Sobremesas', 'icone' => '🍮', 'ordem' => 5, 'ativo' => 1],
            ['nome' => 'Bebidas', 'icone' => '🥤', 'ordem' => 6, 'ativo' => 1],
            ['nome' => 'Combos', 'icone' => '🍱', 'ordem' => 7, 'ativo' => 1],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert([
                'nome' => $categoria['nome'],
                'icone' => $categoria['icone'],
                'ordem' => $categoria['ordem'],
                'ativo' => $categoria['ativo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Configurações padrão
        $configuracoes = [
            ['chave' => 'restaurante_nome', 'valor' => 'Restaurante Bella Cucina', 'tipo' => 'texto', 'descricao' => 'Nome do restaurante'],
            ['chave' => 'restaurante_telefone', 'valor' => '(11) 99999-9999', 'tipo' => 'texto', 'descricao' => 'Telefone de contato'],
            ['chave' => 'taxa_entrega_padrao', 'valor' => '8.00', 'tipo' => 'numero', 'descricao' => 'Taxa de entrega padrão em R$'],
            ['chave' => 'raio_entrega_km', 'valor' => '10', 'tipo' => 'numero', 'descricao' => 'Raio máximo de entrega em km'],
            ['chave' => 'tempo_estimado_entrega', 'valor' => '45', 'tipo' => 'numero', 'descricao' => 'Tempo estimado de entrega em minutos'],
            ['chave' => 'pedido_minimo', 'valor' => '30.00', 'tipo' => 'numero', 'descricao' => 'Valor mínimo de pedido'],
            ['chave' => 'aceita_retirada', 'valor' => '1', 'tipo' => 'booleano', 'descricao' => 'Aceita retirada no local'],
            ['chave' => 'aceita_agendamento', 'valor' => '0', 'tipo' => 'booleano', 'descricao' => 'Aceita pedidos agendados'],
            ['chave' => 'pix_chave', 'valor' => '', 'tipo' => 'texto', 'descricao' => 'Chave PIX do restaurante'],
            ['chave' => 'instagram', 'valor' => '', 'tipo' => 'texto', 'descricao' => 'Link do Instagram'],
            ['chave' => 'whatsapp', 'valor' => '', 'tipo' => 'texto', 'descricao' => 'Número WhatsApp para contato'],
        ];

        foreach ($configuracoes as $config) {
            DB::table('configuracoes')->insert([
                'chave' => $config['chave'],
                'valor' => $config['valor'],
                'tipo' => $config['tipo'],
                'descricao' => $config['descricao'],
                'updated_at' => now(),
            ]);
        }

        // Horários padrão (Seg-Sex 11h-23h, Sab-Dom 11h-23h30)
        $horarios = [
            ['dia_semana' => 0, 'abertura' => '11:00', 'fechamento' => '23:30', 'ativo' => 1], // Domingo
            ['dia_semana' => 1, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Segunda
            ['dia_semana' => 2, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Terça
            ['dia_semana' => 3, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Quarta
            ['dia_semana' => 4, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Quinta
            ['dia_semana' => 5, 'abertura' => '11:00', 'fechamento' => '23:30', 'ativo' => 1], // Sexta
            ['dia_semana' => 6, 'abertura' => '11:00', 'fechamento' => '23:30', 'ativo' => 1], // Sábado
        ];

        foreach ($horarios as $horario) {
            DB::table('horarios_funcionamento')->insert($horario);
        }
    }
}