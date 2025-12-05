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
        Schema::create('tema_baju', function (Blueprint $table) {
            $table->id();
            $table->string('nama');            // Nama tema baju
            $table->longText('images')->nullable(); // JSON path gambar
            $table->text('detail');            // Deskripsi atau detail baju
            $table->string('designer');        // Nama desainer
            $table->decimal('harga', 15, 2);   // Harga
            $table->string('kode')->unique();  // Kode unik baju
            $table->string('ukuran');          // Ukuran baju (S, M, L, dst.)
            $table->string('tipe');            // Tipe baju (casual, formal, dll.)

            // ➕ Tambahan yang kamu mau langsung di sini
            $table->integer('order')->nullable();     // Urutan tampilan
            $table->boolean('active')->default(1);    // Status aktif / nonaktif

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tema_baju');
    }
};
