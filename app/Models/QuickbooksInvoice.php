<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuickbooksInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'quickbooks_id',
        'customer_id',
        'salesforce_opportunity_id',
        'name',
        'string_value',
        'doc_number',
        'txn_date',
        'currency_ref',
        'exchange_rate',
        'total_amount',
        'balance',
        'create_time',
        'last_updated_time'
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(QuickbooksPayment::class, 'quickbooks_invoice_id', 'quickbooks_id');
    }
}
