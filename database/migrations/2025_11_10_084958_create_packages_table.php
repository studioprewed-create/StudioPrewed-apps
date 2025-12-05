<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('durasi')->nullable(); // menit atau jam

            // tambahan lama
            $table->string('images')->nullable();          // path gambar
            $table->decimal('discount', 5, 2)->default(0); // persen
            $table->text('notes')->nullable();   
            $table->text('konsep')->nullable();  
            $table->text('rules')->nullable();  

            // ➕ tambahan baru langsung di sini
            $table->integer('order')->nullable();      // urutan tampilan
            $table->boolean('active')->default(1);     // status aktif / nonaktif

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
