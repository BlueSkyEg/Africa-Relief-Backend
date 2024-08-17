<?php

namespace App\Modules\Quickbooks\Repositories;

use App\Modules\Quickbooks\QuickbooksSalesReceipt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class QuickbooksReceiptRepository
{

    /**
     * @return Collection|null
     */
    public function getAll(): ?Collection
    {
        return QuickbooksSalesReceipt::limit(5)->get();
    }


    /**
     * @return string|null
     */
    public function getLastUpdatedTime(): ?string
    {
        $latestUpdatedTime = QuickbooksSalesReceipt::max('last_updated_time');

        return $latestUpdatedTime ? Carbon::parse($latestUpdatedTime)->format('Y-m-d\TH:i:s-07:00') : null;
    }


    /**
     * @param $receipt
     * @return QuickbooksSalesReceipt
     */
    public function updateOrCreate($receipt): QuickbooksSalesReceipt
    {
        return QuickbooksSalesReceipt::updateOrCreate(
            ['quickbooks_id' => $receipt->Id],
            [
                'customer_id' => $receipt->CustomerRef,
                'name' => $receipt->CustomField?->Name,
                'string_value' => $receipt->CustomField?->StringValue,
                'doc_number' => $receipt->DocNumber,
                'txn_date' => $receipt->TxnDate,
                'currency_ref' => $receipt->CurrencyRef,
                'exchange_rate' => $receipt->ExchangeRate,
                'total_amount' => $receipt->TotalAmt,
                'payment_method_ref' => $receipt->PaymentMethodRef,
                'create_time' => Carbon::parse($receipt->MetaData?->CreateTime)->toDateTimeString(),
                'last_updated_time' => Carbon::parse($receipt->MetaData?->LastUpdatedTime)->toDateTimeString()
            ]
        );
    }
}
