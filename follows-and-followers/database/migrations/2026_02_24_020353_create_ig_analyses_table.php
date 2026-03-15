<?php

declare(strict_types=1);

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
        Schema::create('ig_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // The result: users that don't follow back
            $table->json('non-followers');

            // Stats snapshot at analysis time
            $table->unsignedInteger('followers_count');
            $table->unsignedInteger('following_count');
            $table->unsignedInteger('non_followers_count');

            $table->timestamp('processed_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('sale_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ig_analyses');
    }
};
