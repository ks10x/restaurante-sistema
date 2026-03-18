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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider', 32); // 'google', 'facebook'
            $table->string('provider_id');
            $table->text('token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_id'], 'uq_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};