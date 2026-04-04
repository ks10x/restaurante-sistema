<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 7)->default('#FFFFFF');
            $table->string('secondary_color', 7)->default('#002D72');
            $table->string('hero_title')->default('Sabor que aquece a alma');
            $table->string('hero_subtitle')->default('A verdadeira essencia da gastronomia');
            $table->string('delivery_time')->default('~45 min');
            $table->string('location_text')->default('Guaianases e regiao');
            $table->decimal('rating_score', 2, 1)->default(4.9);
            $table->string('rating_label')->default('(High Tech)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_configs');
    }
};
