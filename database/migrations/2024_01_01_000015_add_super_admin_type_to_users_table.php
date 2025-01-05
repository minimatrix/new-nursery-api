<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify the type column to allow super_admin
            DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('staff', 'parent', 'super_admin')");
            // Make nursery_id nullable for super admins
            $table->foreignId('nursery_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('staff', 'parent')");
            $table->foreignId('nursery_id')->nullable(false)->change();
        });
    }
};
