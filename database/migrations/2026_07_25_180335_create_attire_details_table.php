<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attire_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tema_baju_id')
                ->constrained('tema_baju')
                ->cascadeOnDelete();

            $table->string('group', 30);
            $table->string('content');
            $table->unsignedInteger('order')->default(1);

            $table->timestamps();

            $table->index([
                'tema_baju_id',
                'group',
                'order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attire_details');
    }
};