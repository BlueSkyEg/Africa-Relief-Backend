<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickbooksTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'quickbooks_id',
        'doc_number',
        'type',
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
        'payment_method_ref',
        'created_time',
        'last_updated_time'
    ];

    protected $casts = [
        'created_time' => 'datetime:Y-m-d\TH:i:s\Z',
        'last_updated_time' => 'datetime:Y-m-d\TH:i:s\Z'
    ];

    protected function createdTime(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::parse($value)->toDateTimeString()
        );
    }

    protected function lastUpdatedTime(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::parse($value)->toDateTimeString()
        );
    }
}
