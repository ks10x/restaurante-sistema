<?php

namespace Database\Seeders;

use App\Models\RestaurantConfig;
use App\Models\User;
use Illuminate\Database\Seeder;

class RestaurantConfigSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->orderBy('id')
            ->first();

        if (!$admin) {
            return;
        }

        RestaurantConfig::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'primary_color' => '#FFFFFF',
                'secondary_color' => '#002D72',
                'hero_title' => 'Sabor que aquece a alma',
                'hero_subtitle' => 'A verdadeira essencia da gastronomia',
                'delivery_time' => '~45 min',
                'location_text' => 'Guaianases e regiao',
                'rating_score' => 4.9,
                'rating_label' => '(High Tech)',
            ]
        );
    }
}
