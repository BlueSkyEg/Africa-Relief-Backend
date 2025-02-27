<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickbooksPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'quickbooks_id',
        'quickbooks_invoice_id',
        'salesforce_payment_id',
        'txn_date',
        'currency_ref',
        'exchange_rate',
        'total_amount',
        'payment_method_ref',
        'create_time',
        'last_updated_time'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(QuickbooksInvoice::class);
    }
}
