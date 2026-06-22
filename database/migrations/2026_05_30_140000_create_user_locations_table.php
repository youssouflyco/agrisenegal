<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('region')->nullable();
            $table->string('kind', 30)->default('autre');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_primary')->default(false);
            $table->boolean('publicly_visible')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_primary']);
            $table->index(['region', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_locations');
    }
};