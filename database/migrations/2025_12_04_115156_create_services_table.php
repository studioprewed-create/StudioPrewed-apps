<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // Natural Expression, Beauty Shoot, dll
            $table->text('description')->nullable(); // deskripsi singkat
            $table->string('image');                 // path gambar
            $table->string('category')->nullable();  // prewed, family, maternity, dll
            $table->unsignedInteger('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['category', 'active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
