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
        Schema::create('data_diri', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data diri utama
            $table->string('nama');
            $table->string('phone')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_pernikahan')->nullable();

            // Data pasangan
            $table->string('nama_pasangan')->nullable();
            $table->string('phone_pasangan')->nullable();
            $table->enum('jenis_kelamin_pasangan', ['laki-laki', 'perempuan'])->nullable();
            $table->date('tanggal_lahir_pasangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_diri');
    }
};
