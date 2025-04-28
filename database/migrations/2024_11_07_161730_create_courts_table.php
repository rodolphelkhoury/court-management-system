<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('court_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('surface_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('court_type_surface_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_type_id')->constrained('court_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('surface_type_id')->constrained('surface_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complex_id')->constrained('complexes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('court_type_id')->constrained('court_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('surface_type_id')->constrained('surface_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('hourly_rate');
            $table->boolean('divisible')->default(false);
            $table->integer('max_divisions')->default(1);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('address_line');
            $table->decimal('reservation_duration', 4, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->decimal('hourly_rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
