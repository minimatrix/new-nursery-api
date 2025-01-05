<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_bands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursery_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('min_age_months');
            $table->integer('max_age_months');
            $table->decimal('hourly_rate', 8, 2);
            $table->decimal('grant_rate', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Ensure age ranges don't overlap within the same nursery
            $table->unique(['nursery_id', 'min_age_months', 'max_age_months'], 'unique_age_range');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_bands');
    }
};
