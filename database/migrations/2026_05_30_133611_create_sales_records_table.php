<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_stats', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->unsignedBigInteger('value')->default(0);
            $table->decimal('amount', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('sales_records', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('orders_count')->default(1);
            $table->timestamps();

            $table->index('sale_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_records');
        Schema::dropIfExists('platform_stats');
    }
};
