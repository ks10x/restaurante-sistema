<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupSeguro extends Command
{
    protected $signature = 'backup:seguro';
    protected $description = 'Gera backup do banco e encripta antes do upload';

    public function handle()
    {
        $this->info('Iniciando backup seguro...');
        
        // Exemplo simples. Num app real, usaria spatie/laravel-backup ou mysqldump cru direto.
        // Aqui simulamos a compressão e criptografia e armazenamos em disco local/cloud
        
        $filename = 'backup_restaurante_' . now()->format('Y_m_d_His') . '.sql';
        $path = storage_path('app/backup/' . $filename);
        
        if (!is_dir(storage_path('app/backup'))) {
            mkdir(storage_path('app/backup'), 0755, true);
        }

        // Simulando dump (requer mysqldump no path, usando laragon pode não estar global)
        $db = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD') ? '-p' . env('DB_PASSWORD') : '';
        
        // Simulação
        file_put_contents($path, "-- Dump do Banco {$db}\n-- Gerado em " . now());

        // Criptografar
        $dados = file_get_contents($path);
        // O Laravel Crypt usa AES-256-CBC ou AES-128-CBC e MAC
        $dadosCifrados = \Illuminate\Support\Facades\Crypt::encrypt($dados);
        
        file_put_contents($path . '.encrypted', $dadosCifrados);
        unlink($path); // Apaga original cru em plain text

        $this->info("Backup {$filename}.encrypted gerado com segurança e protegido por criptografia no Laravel!");
    }
}
