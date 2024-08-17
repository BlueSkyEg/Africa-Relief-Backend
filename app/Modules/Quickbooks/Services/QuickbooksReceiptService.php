<?php

namespace App\Modules\Quickbooks\Services;

use App\Modules\Quickbooks\QuickbooksSalesReceipt;
use App\Modules\Quickbooks\Repositories\QuickbooksReceiptRepository;
use Illuminate\Database\Eloquent\Collection;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickbooksReceiptService
{
    public function __construct(
        private readonly QuickbooksReceiptRepository $quickbooksReceiptRepository,
        private readonly QuickbooksEntityService    $quickbooksEntityService
    )
    {
    }

    public function getAllReceipts(): ?Collection
    {
        return $this->quickbooksReceiptRepository->getAll();
    }

    public function updateOrCreateReceipt($receipt): QuickbooksSalesReceipt
    {
        return $this->quickbooksReceiptRepository->updateOrCreate($receipt);
    }

    public function getReceiptsLastUpdatedTime(): ?string
    {
        return $this->quickbooksReceiptRepository->getLastUpdatedTime();
    }

    /**
     * This function get all SalesReceipt from QuickBooks then store them in the database.
     *
     * @throws ServiceException
     * @throws SdkException
     */
    public function syncReceiptsFromQuickbooks(string $whereClause = null): array
    {
        // Increase the max execution time of quickbooks
        ini_set('max_execution_time', 3600);

        $dataService = $this->quickbooksEntityService->configureDataService();

        $startPosition = 1;
        $receipts = $dataService->Query("Select * from SalesReceipt $whereClause ORDERBY Id", $startPosition);

        $newReceipts = [];

        while ($receipts !== null) {
            foreach ($receipts as $receipt) {
                $newReceipts[] = $this->updateOrCreateReceipt($receipt);
            }

            $startPosition += 100;
            $receipts = $dataService->Query("Select * from SalesReceipt $whereClause ORDERBY Id", $startPosition);
        }

        return $newReceipts;
    }
}
