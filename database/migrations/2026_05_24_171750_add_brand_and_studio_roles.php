<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'ADMIN',
                'CREATIVE_DIRECTOR',
                'ATTIRE',
                'CLIENT',
                'DIREKTUR',
                'EDITOR',
                'PHOTOGRAFER',
                'MAKE_UP',
                'VIDEOGRAFER',
                'MANAGER',
                'MARKETING',
                'STYLISH',
                'ADMIN_ATTIRE',
                'FITTER',
                'BRAND_PARTNERSHIP',
                'STUDIO'
            ) DEFAULT 'CLIENT'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'ADMIN',
                'CREATIVE_DIRECTOR',
                'ATTIRE',
                'CLIENT',
                'DIREKTUR',
                'EDITOR',
                'PHOTOGRAFER',
                'MAKE_UP',
                'VIDEOGRAFER',
                'MANAGER',
                'MARKETING',
                'STYLISH',
                'ADMIN_ATTIRE',
                'FITTER'
            ) DEFAULT 'CLIENT'
        ");
    }
};