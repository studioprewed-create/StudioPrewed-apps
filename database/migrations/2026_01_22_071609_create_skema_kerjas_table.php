<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skema_kerjas', function (Blueprint $table) {
            $table->id();

            // 1 booking = 1 jadwal kerja
            $table->foreignId('booking_client_id')
                  ->constrained('booking_clients')
                  ->cascadeOnDelete()
                  ->unique();

            // ===== PENUGASAN KARYAWAN =====
            $table->foreignId('editor_karyawan_id')->nullable()
                  ->constrained('data_diri_karyawan')->nullOnDelete();

            $table->foreignId('photografer_karyawan_id')->nullable()
                  ->constrained('data_diri_karyawan')->nullOnDelete();

            $table->foreignId('videografer_karyawan_id')->nullable()
                  ->constrained('data_diri_karyawan')->nullOnDelete();

            $table->foreignId('makeup_karyawan_id')->nullable()
                  ->constrained('data_diri_karyawan')->nullOnDelete();

            $table->foreignId('attire_karyawan_id')->nullable()
                  ->constrained('data_diri_karyawan')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skema_kerjas');
    }
};
