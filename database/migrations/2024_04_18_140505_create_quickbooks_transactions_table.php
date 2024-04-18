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
            $table->string('quickbooks_id');
            $table->string('doc_number');
            $table->date('txn_date');
            $table->date('due_date');
            $table->string('currency');
            $table->decimal('total_amount', 18);
            $table->text('description');
            $table->text('customer_memo');
            $table->string('billing_email');
            $table->string('billing_address_1');
            $table->string('billing_address_2');
            $table->string('billing_city');
            $table->string('billing_country');
            $table->string('billing_postal_code');
            $table->string('payment_method_ref');
            $table->timestamps();
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
