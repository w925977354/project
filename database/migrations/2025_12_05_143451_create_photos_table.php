<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Create the photos table to store user-uploaded images with metadata.
     */
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign key linking to users table (photo owner)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Photo metadata
            $table->string('title');
            $table->text('description')->nullable();

            // File storage path (relative path in storage/app/public/photos)
            $table->string('image_path');

            // Timestamp columns (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Drop the photos table when rolling back.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
