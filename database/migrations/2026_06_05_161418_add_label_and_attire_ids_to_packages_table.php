<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {

            $table->unsignedBigInteger('label_id')
                ->nullable()
                ->after('rules');

            $table->text('attire_ids')
                ->nullable()
                ->after('label_id');
            $table->json('tac_ids')
                ->nullable()
                ->after('attire_ids');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {

            $table->dropColumn([
                'label_id',
                'attire_ids',
                'tac_ids',
            ]);

        });
    }
};