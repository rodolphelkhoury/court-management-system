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
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('disk', [ 'local', 'public', 's3' ]);
            $table->string('name');
            $table->string('filepath');
            $table->string('mimetype')->nullable();
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->unsignedMediumInteger('filesize');
            $table->morphs('owner');
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
