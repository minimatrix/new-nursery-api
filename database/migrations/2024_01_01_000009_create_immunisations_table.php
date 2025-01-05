<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immunisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursery_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('requires_dates')->default(true);
            $table->timestamps();
        });

        Schema::create('child_immunisation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->foreignId('immunisation_id')->constrained()->onDelete('cascade');
            $table->date('date_given')->nullable();
            $table->date('date_due')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_immunisation');
        Schema::dropIfExists('immunisations');
    }
};
