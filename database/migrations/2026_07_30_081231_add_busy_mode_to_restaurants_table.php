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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('is_busy')->default(false)->after('is_featured');
            $table->timestamp('busy_until')->nullable()->after('is_busy');
            $table->string('busy_reason')->nullable()->after('busy_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('is_busy');
            $table->dropColumn('busy_until');
            $table->dropColumn('busy_reason');
        });
    }
};
