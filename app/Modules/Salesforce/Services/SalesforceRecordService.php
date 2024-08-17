<?php

namespace App\Modules\Salesforce\Services;

use App\Modules\Quickbooks\Services\QuickbooksInvoiceService;
use App\Modules\Quickbooks\Services\QuickbooksPaymentService;
use App\Modules\Quickbooks\Services\QuickbooksReceiptService;
use Illuminate\Support\Facades\Http;

class SalesforceRecordService
{
    public function __construct(
        private readonly SalesforceAuthenticateService $salesforceAuthenticateService,
        private readonly QuickbooksReceiptService      $quickbooksReceiptService,
        private readonly QuickbooksInvoiceService      $quickbooksInvoiceService,
        private readonly QuickbooksPaymentService      $quickbooksPaymentService,
    )
    {
    }

    public function createSalesforceRecord(string $entity, $transaction, string $accessToken): ?string
    {
        $recordName = $transaction->string_value ?: $transaction->txn_date;

        $opportunityData = [
            "Name" => $recordName,
            "StageName" => 'Closed Won',
            "CloseDate" => $transaction->txn_date,
            "Amount" => $transaction->total_amount
        ];

        $paymentData = [
            "Name" => $recordName,
            "npe01__Opportunity__c" => $transaction->salesforce_opportunity_id,
            "npe01__Payment_Date__c" => $transaction->txn_date,
            "npe01__Payment_Amount__c" => $transaction->total_amount,
            "npe01__Payment_Method__c" => $transaction->payment_method_ref
        ];

        $response = Http::withToken($accessToken)
            ->withBody(json_encode($entity === 'opportunity' ? $opportunityData : $paymentData))
            ->post(config('salesforce.endpoint') . "/$entity");

        $response = json_decode($response->body());

        if (isset($response->success)) {
            return $response->id;
        }

        return null;
    }

    public function updateSalesforceRecord(string $entity, $transaction, string $accessToken): bool
    {
        $opportunityData = [
            "Name" => $transaction->string_value ?: $transaction->txn_date,
            "CloseDate" => $transaction->txn_date,
            "Amount" => $transaction->total_amount,
        ];

        $paymentData = [
            "Name" => $transaction->string_value ?: $transaction->txn_date,
            "npe01__Payment_Date__c" => $transaction->txn_date,
            "npe01__Payment_Amount__c" => $transaction->total_amount,
            "npe01__Payment_Method__c" => $transaction->payment_method_ref
        ];

        $recordData = $opportunityData;
        $recordId = $transaction->salesforce_opportunity_id;

        if ($entity !== 'opportunity') {
            $recordData = $paymentData;
            $recordId = $transaction->salesforce_payment_id;
        }

        $response = Http::withToken($accessToken)
            ->withBody(json_encode($recordData))
            ->post(config('salesforce.endpoint') . "/$entity" . "/$recordId");

        if ($response->noContent()) {
            return true;
        }

        return false;
    }

    public function syncAllQuickbooksReceiptsToSalesforce(): void
    {
        $accessToken = $this->salesforceAuthenticateService->getSalesforceAccessToken();

        $receipts = $this->quickbooksReceiptService->getAllReceipts();

        foreach ($receipts as $receipt) {
            $opportunityId = $this->createSalesforceRecord('opportunity', $receipt, $accessToken);
            $oppPaymentId = $this->createSalesforceRecord('npe01__OppPayment__c', $receipt, $accessToken);

            $receipt->salesforce_opportunity_id = $opportunityId;
            $receipt->salesforce_payment_id = $oppPaymentId;
            $receipt->save();
        }
    }

    public function syncAllQuickbooksInvoicesToSalesforce(): void
    {
        $accessToken = $this->salesforceAuthenticateService->getSalesforceAccessToken();

        $invoices = $this->quickbooksInvoiceService->getAllInvoices();

        foreach ($invoices as $invoice) {
            $opportunityId = $this->createSalesforceRecord('opportunity', $invoice, $accessToken);

            $invoice->salesforce_opportunity_id = $opportunityId;
            $invoice->save();
        }
    }

    public function syncAllQuickbooksPaymentsToSalesforce(): void
    {
        $accessToken = $this->salesforceAuthenticateService->getSalesforceAccessToken();

        $payments = $this->quickbooksPaymentService->getAllPayments();

        foreach ($payments as $payment) {
            $oppPaymentId = $this->createSalesforceRecord('npe01__OppPayment__c', $payment, $accessToken);

            $payment->salesforce_payment_id = $oppPaymentId;
            $payment->save();
        }
    }
}
