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
        Schema::create('google_reviews', function (Blueprint $table) {

            $table->id();
            $table->string('review_id')->unique();
            $table->string('author_name');
            $table->integer('rating');
            $table->longText('review_text')->nullable();
            $table->text('profile_photo')->nullable();
            $table->json('review_images')->nullable();
            $table->integer('likes_count')->default(0);
            $table->timestamp('review_date')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('google_reviews');
    }
};
