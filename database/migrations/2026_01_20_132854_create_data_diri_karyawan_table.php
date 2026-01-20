<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_diri_karyawan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Data utama
            $table->string('nama_lengkap');
            $table->string('role');

            // Data pribadi
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('status_pernikahan', ['Lajang', 'Menikah', 'Cerai'])->nullable();
            $table->string('kewarganegaraan')->default('Indonesia');
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('foto')->nullable();

            // Data kepegawaian
            $table->enum('status_karyawan', ['Tetap', 'Kontrak', 'Magang', 'Freelance'])->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_diri_karyawan');
    }
};
