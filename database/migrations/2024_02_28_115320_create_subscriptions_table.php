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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained();
            $table->foreignId('donation_form_id')->nullable()->constrained();
            $table->string('period', 20)->nullable();
            $table->decimal('initial_amount', 18, 10)->nullable();
            $table->decimal('recurring_amount', 18, 10)->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->bigInteger('parent_payment_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('expiration_at')->nullable();
            $table->string('status', 20)->nullable();
            $table->longText('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
