<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickbooksTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'quickbooks_id',
        'doc_number',
        'txn_date',
        'due_date',
        'currency',
        'total_amount',
        'description',
        'customer_memo',
        'billing_email',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_country',
        'billing_postal_code',
        'payment_method_ref'
    ];
}
