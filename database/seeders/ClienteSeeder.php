<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $target = 25;
        $atual = User::query()->count();

        if ($atual >= $target) {
            return;
        }

        User::factory()->count($target - $atual)->create();
    }
}

