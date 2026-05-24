<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah CONTENT_CREATOR dulu
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'ADMIN',
                'CREATIVE_DIRECTOR',
                'ATTIRE',
                'CONTENT_CREATOR',
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

        // Update data lama
        DB::table('users')
            ->where('role', 'ATTIRE')
            ->update([
                'role' => 'CONTENT_CREATOR'
            ]);

        // Hapus ATTIRE
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'ADMIN',
                'CREATIVE_DIRECTOR',
                'CONTENT_CREATOR',
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
                'CONTENT_CREATOR',
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

        DB::table('users')
            ->where('role', 'CONTENT_CREATOR')
            ->update([
                'role' => 'ATTIRE'
            ]);

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
};