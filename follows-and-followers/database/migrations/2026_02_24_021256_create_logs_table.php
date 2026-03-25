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
        Schema::create('logs', function (Blueprint $table) {
            $table->id()->primary();
            $table
                ->string('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('action'); // e.g 'auth.login', 'sale.created', 'analysis.processed'
            $table
                ->string('entity_type')
                ->nullable(); // 'Auth', 'Sale', 'IgAnalysis'
            $table
                ->uuid('entity_id')
                ->nullable();

            $table
                ->string('ip_address', 45)
                ->nullable(); // 45 chars covers IPv6
            $table
                ->text('user_agent')
                ->nullable();
            $table
                ->jsonb('payload')
                ->nullable();

            $table->timestamp('created_at')->default(now()); // no updated_at, logs are immutable

            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
