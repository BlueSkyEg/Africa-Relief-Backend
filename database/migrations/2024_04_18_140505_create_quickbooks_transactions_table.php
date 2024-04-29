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
        Schema::create('quickbooks_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('quickbooks_id')->nullable();
            $table->string('doc_number')->nullable();
            $table->string('type')->nullable();
            $table->date('txn_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('total_amount', 18)->nullable();
            $table->text('description')->nullable();
            $table->text('customer_memo')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_address_1')->nullable();
            $table->string('billing_address_2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('payment_method_ref')->nullable();
            $table->dateTimeTz('created_time')->nullable();
            $table->dateTimeTz('last_updated_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quickbooks_transactions');
    }
};
