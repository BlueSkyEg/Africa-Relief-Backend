<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickbooksSalesReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quickbooks_id',
        'customer_id',
        'salesforce_opportunity_id',
        'salesforce_payment_id',
        'name',
        'string_value',
        'doc_number',
        'txn_date',
        'currency_ref',
        'exchange_rate',
        'total_amount',
        'payment_method_ref',
        'create_time',
        'last_updated_time'
    ];
}
