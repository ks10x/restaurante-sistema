<?php

namespace Tests\Feature\Admin;

use App\Models\RestaurantConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_white_label_settings(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'phone_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.configuracoes.white-label.update'), [
            'logo' => UploadedFile::fake()->image('logo.png'),
            'primary_color' => '#101010',
            'secondary_color' => '#FFD700',
            'hero_title' => 'Novo sabor',
            'hero_subtitle' => 'Uma nova fase do restaurante',
            'delivery_time' => '~35 min',
            'location_text' => 'Zona Leste',
            'rating_score' => 4.8,
            'rating_label' => '(Clientes)',
        ]);

        $response->assertRedirect(route('admin.configuracoes.index'));

        $config = RestaurantConfig::query()->where('user_id', $admin->id)->first();

        $this->assertNotNull($config);
        $this->assertSame('#101010', $config->primary_color);
        $this->assertSame('#FFD700', $config->secondary_color);
        $this->assertSame('Novo sabor', $config->hero_title);
        $this->assertSame('Zona Leste', $config->location_text);
        $this->assertSame('4.8', $config->rating_score);
        $this->assertNotNull($config->logo_path);
        Storage::disk('public')->assertExists($config->logo_path);
    }

    public function test_admin_can_not_save_invalid_hex_colors(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'phone_verified_at' => now(),
        ]);

        $response = $this->from(route('admin.configuracoes.index'))
            ->actingAs($admin)
            ->put(route('admin.configuracoes.white-label.update'), [
                'primary_color' => 'blue',
                'secondary_color' => '#12345',
                'hero_title' => 'Novo sabor',
                'hero_subtitle' => 'Uma nova fase do restaurante',
                'delivery_time' => '~35 min',
                'location_text' => 'Zona Leste',
                'rating_score' => 4.8,
                'rating_label' => '(Clientes)',
            ]);

        $response->assertRedirect(route('admin.configuracoes.index'));
        $response->assertSessionHasErrors(['primary_color', 'secondary_color']);
        $this->assertDatabaseCount('restaurant_configs', 0);
    }
}
