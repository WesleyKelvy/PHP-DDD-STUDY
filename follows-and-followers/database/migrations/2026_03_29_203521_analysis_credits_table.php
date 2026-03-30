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
        Schema::create('analysis_credits', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('sale_id')->constrained()->cascadeOnDelete();

            $table->integer('total')->default(1);     
            $table->integer('used')->default(0);
            $table->integer('reserved')->default(0);

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
