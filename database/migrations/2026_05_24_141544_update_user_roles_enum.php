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
                'ADMIN_EDITOR',
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

        // Ubah data role lama
        DB::table('users')
            ->where('role', 'ADMIN_EDITOR')
            ->update([
                'role' => 'CREATIVE_DIRECTOR'
            ]);

        // Hapus ADMIN_EDITOR dari enum
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

    public function down(): void
    {
        // Tambahkan kembali ADMIN_EDITOR
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'ADMIN',
                'ADMIN_EDITOR',
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

        // Kembalikan data lama
        DB::table('users')
            ->where('role', 'CREATIVE_DIRECTOR')
            ->update([
                'role' => 'ADMIN_EDITOR'
            ]);

        // Hapus role baru
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'ADMIN',
                'ADMIN_EDITOR',
                'ATTIRE',
                'CLIENT',
                'DIREKTUR',
                'EDITOR',
                'PHOTOGRAFER',
                'MAKE_UP',
                'VIDEOGRAFER'
            ) DEFAULT 'CLIENT'
        ");
    }
};