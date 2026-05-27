<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            $table->json('future_services')
                ->nullable()
                ->after('favorite_services');

        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            $table->dropColumn('future_services');

        });
    }
};