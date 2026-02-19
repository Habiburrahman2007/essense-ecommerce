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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->enum('gateway', ['midtrans', 'xendit', 'manual']);
            $table->string('gateway_transaction_id')->nullable();

            $table->string('payment_type');      // qris, va, ewallet, cc
            $table->string('payment_method');    // gopay, bca_va, ovo

            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('IDR');

            $table->enum('status', [
                'pending', 'success', 'failed', 'expired'
            ])->default('pending');

            $table->string('fraud_status')->nullable();

            $table->json('payload')->nullable(); // full callback JSON
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
