<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tornar nullable todas as colunas opcionais que estavam NOT NULL sem default
        DB::statement('ALTER TABLE users MODIFY COLUMN cpf VARCHAR(20) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE users MODIFY COLUMN phone VARCHAR(30) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE users MODIFY COLUMN avatar VARCHAR(255) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE users MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT "ativo"');
        DB::statement('ALTER TABLE users MODIFY COLUMN last_login_at TIMESTAMP NULL DEFAULT NULL');
    }

    public function down(): void
    {
        // Reversão conservadora — volta como NOT NULL mas pode travar se houver NULLs
        DB::statement('ALTER TABLE users MODIFY COLUMN cpf VARCHAR(20) NOT NULL');
    }
};
