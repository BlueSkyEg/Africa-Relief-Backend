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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('post_id')->constrained();
            $table->foreignId('featured_image_id')->constrained('images');
            $table->foreignId('donation_form_id')->nullable()->constrained(); // or create a new migration for it
            $table->string('location')->nullable();
            $table->date('implementation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
