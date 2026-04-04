<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class RestaurantConfig extends Model
{
    use HasFactory;

    public const CACHE_PREFIX = 'restaurant_config.user.';

    protected $fillable = [
        'user_id',
        'logo_path',
        'primary_color',
        'secondary_color',
        'hero_title',
        'hero_subtitle',
        'delivery_time',
        'location_text',
        'rating_score',
        'rating_label',
    ];

    protected $casts = [
        'rating_score' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        return asset('storage/'.$this->logo_path);
    }

    public static function cacheKeyForUser(int $userId): string
    {
        return self::CACHE_PREFIX.$userId;
    }

    public static function forUser(User $user): self
    {
        if (!Schema::hasTable('restaurant_configs')) {
            return (new self())->forceFill([
                'user_id' => $user->id,
                'primary_color' => '#FFFFFF',
                'secondary_color' => '#002D72',
                'hero_title' => 'Sabor que aquece a alma',
                'hero_subtitle' => 'A verdadeira essencia da gastronomia',
                'delivery_time' => '~45 min',
                'location_text' => 'Guaianases e regiao',
                'rating_score' => 4.9,
                'rating_label' => '(High Tech)',
            ]);
        }

        return Cache::rememberForever(
            self::cacheKeyForUser($user->id),
            fn () => self::firstOrCreate(['user_id' => $user->id])
        );
    }

    public static function storefront(): self
    {
        if (!Schema::hasTable('restaurant_configs')) {
            return new self([
                'primary_color' => '#FFFFFF',
                'secondary_color' => '#002D72',
                'hero_title' => 'Sabor que aquece a alma',
                'hero_subtitle' => 'A verdadeira essencia da gastronomia',
                'delivery_time' => '~45 min',
                'location_text' => 'Guaianases e regiao',
                'rating_score' => 4.9,
                'rating_label' => '(High Tech)',
            ]);
        }

        $owner = auth()->user();

        if (!$owner || !$owner->isAdmin()) {
            $owner = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->orderBy('id')
                ->first();
        }

        if (!$owner) {
            return new self();
        }

        return self::forUser($owner);
    }

    public function flushCache(): void
    {
        Cache::forget(self::cacheKeyForUser($this->user_id));
    }
}
