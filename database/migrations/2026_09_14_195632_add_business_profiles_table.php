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
       Schema::create('business_profiles', function (Blueprint $table) {
          $table->id();

          $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
          $table->string('cni')->nullable();
          $table->string('cni_front_photo')->nullable();
          $table->string('cni_back_photo')->nullable();

          $table->string('farm_name')->nullable();
          $table->string('production_type')->nullable();
          $table->string('farm_photo')->nullable();

          $table->string('business_name')->nullable();
          $table->string('business_type')->nullable();
          $table->string('business_photo')->nullable();

          $table->enum('status', ['PENDING','APPROVED','REJECTED'])->default('PENDING');
          $table->text('rejection_reason')->nullable();
          $table->timestamp('approved_at')->nullable();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            Schema::dropIfExists('product_photos');
        });
    }
};
