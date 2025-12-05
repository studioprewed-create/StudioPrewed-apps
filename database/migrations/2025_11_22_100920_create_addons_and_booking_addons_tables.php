<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel master ADDON
        Schema::create('addons', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('kode')->nullable()->unique();
            $table->unsignedTinyInteger('kategori');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('harga')->default(0);

            // GANTI config jadi ini:
            $table->unsignedSmallInteger('durasi')->nullable()
                ->comment('Durasi tambahan dalam menit (misal 60 / 120)');
            $table->unsignedTinyInteger('kapasitas')->nullable()
                ->comment('Kapasitas slot tambahan (misal 2)');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Tabel relasi BOOKING <-> ADDON
        Schema::create('booking_addons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_client_id')
                ->constrained('booking_clients')
                ->cascadeOnDelete();

            $table->foreignId('addon_id')
                ->constrained('addons')
                ->restrictOnDelete();

            // jumlah addon ini di booking
            $table->unsignedInteger('qty')->default(1);

            // snapshot harga di saat booking
            $table->unsignedInteger('harga_satuan');
            $table->unsignedInteger('total_harga');

            // detail khusus per addon di booking ini
            $table->json('meta')->nullable();
            // contoh:
            // kategori 1: {"date":"2025-12-01","start":"13:00","end":"14:00"}
            // kategori 2: {"tema_id":12,"tema_kode":"A-03"}
            // kategori 3: {"ukuran":"50x70","warna":"gold"}

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_addons');
        Schema::dropIfExists('addons');
    }
};
