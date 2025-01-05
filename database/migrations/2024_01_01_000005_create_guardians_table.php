<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // If guardian is a parent user
            $table->string('first_name');
            $table->string('last_name');
            $table->string('relationship');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->boolean('is_emergency_contact')->default(false);
            $table->boolean('can_pickup')->default(false);
            $table->integer('priority')->default(1); // Primary contact = 1, Secondary = 2, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
