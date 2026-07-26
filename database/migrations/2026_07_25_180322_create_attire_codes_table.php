<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attire_codes', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('prefix', 20)->unique();
            $table->string('separator', 3)->default('-');
            $table->unsignedTinyInteger('digit_length')->default(2);
            $table->unsignedBigInteger('last_number')->default(0);

            $table->unsignedInteger('order')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attire_codes');
    }
};