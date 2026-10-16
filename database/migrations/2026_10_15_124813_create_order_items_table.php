
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Keep the item if the original product is deleted.
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            // Seller attribution at the time of purchase.
            $table->foreignId('seller_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Historical snapshots.
            $table->string('product_name');
            $table->string('product_image')->nullable();
            $table->string('seller_name')->nullable();

            $table->decimal('unit_price', 14, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 14, 2);

            $table->timestamps();

            $table->index(['order_id', 'product_id']);
            $table->index(['seller_user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
