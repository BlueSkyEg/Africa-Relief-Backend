<?php

namespace App\Modules\Quickbooks\Repositories;

use App\Models\QuickbooksInvoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class QuickbooksInvoiceRepository
{

    /**
     * @return Collection|null
     */
    public function getAll(): ?Collection
    {
        return QuickbooksInvoice::limit(5)->get();
    }


    /**
     * @return string|null
     */
    public function getLastUpdatedTime(): ?string
    {
        $latestUpdatedTime = QuickbooksInvoice::max('last_updated_time');

        return $latestUpdatedTime ? Carbon::parse($latestUpdatedTime)->format('Y-m-d\TH:i:s-07:00') : null;
    }


    /**
     * @param $invoice
     * @return QuickbooksInvoice
     */
    public function updateOrCreate($invoice): QuickbooksInvoice
    {
        return QuickbooksInvoice::updateOrCreate(
            ['quickbooks_id' => $invoice->Id],
            [
                'customer_id' => $invoice->CustomerRef,
                'name' => $invoice->CustomField?->Name,
                'string_value' => $invoice->CustomField?->StringValue,
                'doc_number' => $invoice->DocNumber,
                'txn_date' => $invoice->TxnDate,
                'currency_ref' => $invoice->CurrencyRef,
                'exchange_rate' => $invoice->ExchangeRate,
                'total_amount' => $invoice->TotalAmt,
                'balance' => $invoice->Balance,
                'create_time' => Carbon::parse($invoice->MetaData?->CreateTime)->toDateTimeString(),
                'last_updated_time' => Carbon::parse($invoice->MetaData?->LastUpdatedTime)->toDateTimeString()
            ]
        );
    }
}
