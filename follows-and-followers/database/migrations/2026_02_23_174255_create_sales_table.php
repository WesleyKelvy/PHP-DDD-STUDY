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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('user_id')->constrained();

            $table->decimal('amount', 8, 2);
            $table
                ->enum('status', [
                    'pending',
                    'approved',
                    'authorized',
                    'in_process',
                    'in_mediation',
                    'rejected',
                    'cancelled',
                    'refunded',
                    'charged_back'])
                ->default('pending');

            // Mercado Pago
            $table->string('mp_payment_id')->unique(); // Mercado Pago payment's ID
            $table->jsonb('mp_payment_data')->nullable(); // Full Mercado Pago webhook payload

            $table->timestamp('paid_at');
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('mp_payment_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
