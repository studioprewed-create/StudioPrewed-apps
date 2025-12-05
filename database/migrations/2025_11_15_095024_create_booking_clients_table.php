<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================
        // JIKA TABEL SUDAH ADA
        // ==========================
        if (Schema::hasTable('booking_clients')) {

            Schema::table('booking_clients', function (Blueprint $table) {
                // ======================= TEMA UTAMA =======================
                if (!Schema::hasColumn('booking_clients', 'tema_id')) {
                    $table->unsignedBigInteger('tema_id')->nullable()->after('end_time');
                }

                if (!Schema::hasColumn('booking_clients', 'tema_nama')) {
                    $table->string('tema_nama')->nullable()->after('tema_id');
                }

                if (!Schema::hasColumn('booking_clients', 'tema_kode')) {
                    $table->string('tema_kode')->nullable()->after('tema_nama');
                }

                // =================== SUMMARY HARGA ==================
                if (!Schema::hasColumn('booking_clients', 'package_price')) {
                    $table->unsignedInteger('package_price')
                          ->default(0)
                          ->after('package_id');
                }

                if (!Schema::hasColumn('booking_clients', 'addons_total')) {
                    $table->unsignedInteger('addons_total')
                          ->default(0)
                          ->after('package_price');
                }

                if (!Schema::hasColumn('booking_clients', 'grand_total')) {
                    $table->unsignedInteger('grand_total')
                          ->default(0)
                          ->after('addons_total');
                }

                // ========== SLOT TAMBAHAN (ADDON KAT.1) ==========
                if (!Schema::hasColumn('booking_clients', 'extra_slot_code')) {
                    $table->string('extra_slot_code', 10)
                          ->nullable()
                          ->after('slot_code');
                }

                if (!Schema::hasColumn('booking_clients', 'extra_photoshoot_slot')) {
                    $table->string('extra_photoshoot_slot', 20)
                          ->nullable()
                          ->after('photoshoot_slot');
                }

                if (!Schema::hasColumn('booking_clients', 'extra_start_time')) {
                    $table->time('extra_start_time')
                          ->nullable()
                          ->after('end_time');
                }

                if (!Schema::hasColumn('booking_clients', 'extra_end_time')) {
                    $table->time('extra_end_time')
                          ->nullable()
                          ->after('extra_start_time');
                }

                if (!Schema::hasColumn('booking_clients', 'extra_minutes')) {
                    $table->integer('extra_minutes')
                          ->nullable()
                          ->after('extra_end_time');
                }

                // ========= TEMA TAMBAHAN (ADDON KAT.2) ==========
                if (!Schema::hasColumn('booking_clients', 'tema2_id')) {
                    $table->unsignedBigInteger('tema2_id')
                          ->nullable()
                          ->after('tema_kode');
                }

                if (!Schema::hasColumn('booking_clients', 'tema2_nama')) {
                    $table->string('tema2_nama', 100)
                          ->nullable()
                          ->after('tema2_id');
                }

                if (!Schema::hasColumn('booking_clients', 'tema2_kode')) {
                    $table->string('tema2_kode', 100)
                          ->nullable()
                          ->after('tema2_nama');
                }
            });

            // FK ke tema_baju untuk tema_id (tema utama)
            if (Schema::hasTable('tema_baju')) {
                try {
                    Schema::table('booking_clients', function (Blueprint $table) {
                        if (! $this->hasForeignKey('booking_clients', 'bk_clients_tema_fk')) {
                            $table->foreign('tema_id', 'bk_clients_tema_fk')
                                  ->references('id')->on('tema_baju')
                                  ->onDelete('set null');
                        }

                        // OPTIONAL: kalau mau FK juga untuk tema2_id, bisa aktifkan ini:
                        /*
                        if (! $this->hasForeignKey('booking_clients', 'bk_clients_tema2_fk')) {
                            $table->foreign('tema2_id', 'bk_clients_tema2_fk')
                                  ->references('id')->on('tema_baju')
                                  ->onDelete('set null');
                        }
                        */
                    });
                } catch (\Throwable $e) {
                    // kalau FK sudah ada / error lain, diamkan saja
                }
            }

            return; // penting: jangan lanjut ke CREATE TABLE
        }

        // ==========================
        // JIKA TABEL BELUM ADA (FRESH)
        // ==========================
        Schema::create('booking_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // -------- IDENTITAS --------
            $table->string('nama_cpp', 100);
            $table->string('nama_cpw', 100);
            $table->string('email_cpp', 120)->nullable();
            $table->string('email_cpw', 120)->nullable();
            $table->string('phone_cpp', 30)->nullable();
            $table->string('phone_cpw', 30)->nullable();
            $table->string('alamat_cpp')->nullable();
            $table->string('alamat_cpw')->nullable();

            // -------- SOSMED --------
            $table->string('ig_cpp')->nullable();
            $table->string('ig_cpw')->nullable();
            $table->string('tiktok_cpp')->nullable();
            $table->string('tiktok_cpw')->nullable();
            $table->json('sosmed_lain')->nullable();

            // -------- PAKET & HARGA --------
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->unsignedInteger('package_price')->default(0);
            $table->unsignedInteger('addons_total')->default(0);
            $table->unsignedInteger('grand_total')->default(0);

            // -------- SLOT UTAMA --------
            $table->date('photoshoot_date');
            $table->string('slot_code', 10);
            $table->string('photoshoot_slot', 20); // "HH:MM-HH:MM"
            $table->time('start_time');
            $table->time('end_time');

            // -------- SLOT TAMBAHAN (ADDON KAT.1) --------
            $table->string('extra_slot_code', 10)->nullable();
            $table->string('extra_photoshoot_slot', 20)->nullable();
            $table->time('extra_start_time')->nullable();
            $table->time('extra_end_time')->nullable();
            $table->integer('extra_minutes')->nullable();

            // -------- TEMA UTAMA --------
            $table->unsignedBigInteger('tema_id')->nullable();
            $table->string('tema_nama')->nullable();
            $table->string('tema_kode')->nullable();

            // -------- TEMA TAMBAHAN (ADDON KAT.2) --------
            $table->unsignedBigInteger('tema2_id')->nullable();
            $table->string('tema2_nama', 100)->nullable();
            $table->string('tema2_kode', 100)->nullable();

            // -------- STYLE & LAIN-LAIN --------
            $table->string('style')->nullable(); // Hair | HairDo
            $table->date('wedding_date')->nullable();
            $table->text('notes')->nullable();

            // -------- RINGKASAN --------
            $table->string('nama_gabungan')->nullable();
            $table->string('email_gabungan')->nullable();
            $table->string('phone_gabungan')->nullable();

            // -------- KODE & STATUS --------
            $table->string('kode_pesanan', 40)->unique();
            $table->enum('status', ['draft','submitted','confirmed','cancelled'])
                  ->default('submitted');

            $table->timestamps();
        });

        // Pasang FK setelah create (kalau tabel tema_baju ada)
        if (Schema::hasTable('tema_baju')) {
            Schema::table('booking_clients', function (Blueprint $table) {
                $table->foreign('tema_id', 'bk_clients_tema_fk')
                      ->references('id')->on('tema_baju')
                      ->onDelete('set null');

                // OPTIONAL: FK tema2_id
                // $table->foreign('tema2_id', 'bk_clients_tema2_fk')
                //       ->references('id')->on('tema_baju')
                //       ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('booking_clients')) {
            try {
                Schema::table('booking_clients', function (Blueprint $table) {
                    // drop FK kalau ada
                    try {
                        $table->dropForeign('bk_clients_tema_fk');
                    } catch (\Throwable $e) {}
                    try {
                        $table->dropForeign('bk_clients_tema2_fk');
                    } catch (\Throwable $e) {}
                });
            } catch (\Throwable $e) {}
        }

        Schema::dropIfExists('booking_clients');
    }

    // Helper cek foreign key (dipakai di atas saat alter table)
    private function hasForeignKey(string $table, string $fkName): bool
    {
        try {
            $conn          = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $conn->listTableDetails($table);
            return $doctrineTable->hasForeignKey($fkName);
        } catch (\Throwable $e) {
            return false;
        }
    }
};
