<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add is_admin column to distinguish administrators from regular users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add boolean column for admin role with default false
            $table->boolean('is_admin')->default(false)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     * Remove is_admin column when rolling back.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the is_admin column
            $table->dropColumn('is_admin');
        });
    }
};
