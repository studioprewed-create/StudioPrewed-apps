<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE packages MODIFY label_id JSON NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE packages MODIFY label_id BIGINT UNSIGNED NULL');
    }
};
