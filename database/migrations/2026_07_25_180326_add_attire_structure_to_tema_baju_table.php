<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tema_baju', function (Blueprint $table) {
            $table->foreignId('attire_code_id')
                ->nullable()
                ->constrained('attire_codes')
                ->restrictOnDelete();

            $table->unsignedBigInteger('code_number')->nullable();

            $table->foreignId('data_brand_id')
                ->nullable()
                ->constrained('data_brand')
                ->nullOnDelete();

            $table->foreignId('konsep_attire_id')
                ->nullable()
                ->constrained('konsep_attires')
                ->nullOnDelete();

            $table->text('label_ids')->nullable();

            $table->text('ukuran_pria')->nullable();
            $table->text('ukuran_wanita')->nullable();

            $table->string('warna')->nullable();

            $table->string('status', 30)
                ->default('ready');

            $table->unique(
                ['attire_code_id', 'code_number'],
                'tema_baju_attire_code_number_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('tema_baju', function (Blueprint $table) {
            $table->dropUnique(
                'tema_baju_attire_code_number_unique'
            );

            $table->dropConstrainedForeignId('attire_code_id');
            $table->dropConstrainedForeignId('data_brand_id');
            $table->dropConstrainedForeignId('konsep_attire_id');

            $table->dropColumn([
                'code_number',
                'label_ids',
                'ukuran_pria',
                'ukuran_wanita',
                'warna',
                'status',
            ]);
        });
    }
};