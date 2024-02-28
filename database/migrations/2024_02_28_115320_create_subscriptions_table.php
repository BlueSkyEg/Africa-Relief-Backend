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
            $table->string('period')->nullable();
            $table->string('recurring_amount')->nullable();
            $table->string('initial_amount')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('parent_payment_id')->nullable();
            $table->string('created')->nullable();
            $table->string('expiration')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
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
