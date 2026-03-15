<?php

declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ig_analyses', function (Blueprint $table) {
            $table->renameColumn('non-followers', 'non_followers');
        });
    }

    public function down(): void
    {
        Schema::table('ig_analyses', function (Blueprint $table) {
            $table->renameColumn('non_followers', 'non-followers');
        });
    }
};
