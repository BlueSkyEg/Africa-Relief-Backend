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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained();
            $table->foreignId('subscription_id')->nullable()->constrained();
            $table->string('stripe_source_id')->nullable();
            $table->string('stripe_transaction_id')->nullable();
            $table->string('payment_total')->nullable();
            $table->string('project_id')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('donor_billing_phone')->nullable();
            $table->string('donor_billing_country')->nullable();
            $table->string('donor_billing_city')->nullable();
            $table->string('donor_billing_state')->nullable();
            $table->string('donor_billing_first_name')->nullable();
            $table->string('donor_billing_last_name')->nullable();
            $table->string('donor_billing_address_1')->nullable();
            $table->string('donor_billing_address_2')->nullable();
            $table->string('donor_billing_zip')->nullable();
            $table->string('donor_billing_comment')->nullable();
            $table->string('completed_date')->nullable();
            $table->string('anonymous_donation')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('payment_donor_ip')->nullable();
            $table->string('cs_exchange_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
