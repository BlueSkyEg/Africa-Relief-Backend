<?php

namespace App\Modules\Quickbooks\Repositories;

use App\Models\QuickbooksPayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class QuickbooksPaymentRepository
{

    /**
     * @return Collection|null
     */
    public function getAll(): ?Collection
    {
        return QuickbooksPayment::limit(5)->get();
    }


    /**
     * @return string|null
     */
    public function getLastUpdatedTime(): ?string
    {
        $latestUpdatedTime = QuickbooksPayment::max('last_updated_time');

        return $latestUpdatedTime ? Carbon::parse($latestUpdatedTime)->format('Y-m-d\TH:i:s-07:00') : null;
    }


    /**
     * @param $payment
     * @return QuickbooksPayment
     */
    public function updateOrCreate($payment): QuickbooksPayment
    {
        return QuickbooksPayment::updateOrCreate(
            ['quickbooks_id' => $payment->Id],
            [
                'quickbooks_invoice_id' => is_array($payment->Line) ? $payment->Line[0]->LinkedTxn->TxnId : $payment->Line->LinkedTxn->TxnId,
                'doc_number' => $payment->DocNumber,
                'txn_date' => $payment->TxnDate,
                'currency_ref' => $payment->CurrencyRef,
                'exchange_rate' => $payment->ExchangeRate,
                'total_amount' => $payment->TotalAmt,
                'payment_method_ref' => $payment->PaymentMethodRef,
                'create_time' => Carbon::parse($payment->MetaData->CreateTime)->toDateTimeString(),
                'last_updated_time' => Carbon::parse($payment->MetaData->LastUpdatedTime)->toDateTimeString()
            ]
        );
    }
}
