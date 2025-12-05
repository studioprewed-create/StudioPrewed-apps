<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hero_contents', function (Blueprint $table) {
            $table->id();
            $table->string('image');              // path file gambar
            $table->boolean('active')->default(1);// apakah ditampilkan
            $table->unsignedInteger('order')->default(0); // urutan tampilan
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('hero_contents');
    }
};
