
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number', 40)->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('delivery_fee', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);

            // Order lifecycle.
            $table->string('status', 30)->default('PENDING');

            // Payment is independent of order fulfilment.
            $table->string('payment_method', 30)->nullable();
            $table->string('payment_status', 30)->default('PENDING');
            $table->string('payment_reference')->nullable();

            // Pickup or delivery.
            $table->string('delivery_method', 20);

            // Snapshot: recipient, phone, address, city, etc.
            $table->json('delivery_address')->nullable();

            $table->text('customer_note')->nullable();

            $table->timestamp('placed_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
