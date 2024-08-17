<?php

namespace App\Http\Controllers;

use App\Modules\Quickbooks\Services\QuickbooksAuthorizeService;
use App\Modules\Quickbooks\Services\QuickbooksEntityService;
use App\Modules\Quickbooks\Services\QuickbooksInvoiceService;
use App\Modules\Quickbooks\Services\QuickbooksPaymentService;
use App\Modules\Quickbooks\Services\QuickbooksReceiptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickBooksController extends Controller
{
    public function __construct(
        private readonly QuickbooksAuthorizeService $quickbooksAuthorizeService,
        private readonly QuickbooksEntityService    $quickbooksEntityService,
        private readonly QuickbooksReceiptService   $quickbooksReceiptService,
        private readonly QuickbooksInvoiceService   $quickbooksInvoiceService,
        private readonly QuickbooksPaymentService   $quickbooksPaymentService
    )
    {
    }

    public function sendAuthorizationMail(): JsonResponse
    {
        try {
            $this->quickbooksAuthorizeService->sendAuthorizationMail();

            return response()->success('Authorization mail sent successfully.');
        } catch (SdkException $e) {
            return response()->error($e->getMessage());
        }
    }

    public function handleCallbackAfterAuthorization(Request $request): JsonResponse
    {
        try {
            $this->quickbooksAuthorizeService->handleCallbackAfterAuthorization($request->query('code'), $request->query('realmId'));

            return response()->success('Authorization Succeed');
        } catch (SdkException $e) {
            Log::debug('An error occurred during quickbooks authorization from SdkException: ' . $e->getMessage() . ' With status code: ' . $e->getCode());
            return response()->error('Authorization Failed');
        } catch (ServiceException $e) {
            Log::debug('An error occurred during quickbooks authorization from ServiceException: ' . $e->getMessage() . ' With status code: ' . $e->getCode());
            return response()->error('Authorization Failed');
        }
    }

    public function syncSalesReceiptsFromQuickbooks(): JsonResponse
    {
        try {
            $this->quickbooksReceiptService->syncReceiptsFromQuickbooks();

            return response()->success('SalesReceipts synced from QuickBooks successfully.');
        } catch (SdkException|ServiceException $e) {
            return response()->error($e->getMessage());
        }
    }

    public function syncInvoicesFromQuickbooks(): JsonResponse
    {
        try {
            $this->quickbooksInvoiceService->syncInvoicesFromQuickbooks();

            return response()->success('Invoices synced from QuickBooks successfully.');
        } catch (SdkException|ServiceException $e) {
            return response()->error($e->getMessage());
        }
    }

    public function syncPaymentFromQuickbooks(): JsonResponse
    {
        try {
            $this->quickbooksPaymentService->syncPaymentsFromQuickbooks();

            return response()->success('Payments synced from QuickBooks successfully.');
        } catch (SdkException|ServiceException $e) {
            return response()->error($e->getMessage());
        }
    }

    public function getEntityFromQuickbooks(string $entity)
    {
        try {
            $transactions = $this->quickbooksEntityService->getEntityFromQuickbooks($entity);

            return response()->success('Transactions retrieved successfully', $transactions);
        } catch (SdkException|ServiceException $e) {
            return response()->error($e->getMessage());
        }
    }
}
