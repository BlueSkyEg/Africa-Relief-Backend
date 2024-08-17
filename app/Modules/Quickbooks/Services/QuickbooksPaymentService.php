<?php

namespace App\Modules\Quickbooks\Services;

use App\Modules\Quickbooks\QuickbooksPayment;
use App\Modules\Quickbooks\Repositories\QuickbooksPaymentRepository;
use Illuminate\Database\Eloquent\Collection;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickbooksPaymentService
{
    public function __construct(
        private readonly QuickbooksPaymentRepository $quickbooksPaymentRepository,
        private readonly QuickbooksEntityService     $quickbooksEntityService
    )
    {
    }

    public function getAllPayments(): ?Collection
    {
        return $this->quickbooksPaymentRepository->getAll();
    }

    public function updateOrCreatePayment($payment): QuickbooksPayment
    {
        return $this->quickbooksPaymentRepository->updateOrCreate($payment);
    }

    public function getPaymentsLastUpdatedTime(): ?string
    {
        return $this->quickbooksPaymentRepository->getLastUpdatedTime();
    }


    /**
     * This function get all Payments from QuickBooks then store them in the database.
     *
     * @throws ServiceException
     * @throws SdkException
     */
    public function syncPaymentsFromQuickbooks(string $whereClause = null): array
    {
        // Increase the max execution time of quickbooks
        ini_set('max_execution_time', 3600);

        $dataService = $this->quickbooksEntityService->configureDataService();

        $startPosition = 1;
        $payments = $dataService->Query("Select * from Payment $whereClause ORDERBY Id", $startPosition);

        $newPayments = [];

        while ($payments !== null) {
            foreach ($payments as $payment) {
                if (empty($payment->Line)) continue;
                $newPayments[] = $this->updateOrCreatePayment($payment);
            }

            $startPosition += 100;
            $payments = $dataService->Query("Select * from Payment $whereClause ORDERBY Id", $startPosition);
        }

        return $newPayments;
    }
}
