<?php

namespace App\Modules\Quickbooks\Services;

use App\Models\QuickbooksInvoice;
use App\Modules\Quickbooks\Repositories\QuickbooksInvoiceRepository;
use Illuminate\Database\Eloquent\Collection;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickbooksInvoiceService
{
    public function __construct(
        private readonly QuickbooksInvoiceRepository $quickbooksInvoiceRepository,
        private readonly QuickbooksEntityService     $quickbooksEntityService
    )
    {
    }

    public function getAllInvoices(): ?Collection
    {
        return $this->quickbooksInvoiceRepository->getAll();
    }

    public function updateOrCreateInvoice($invoice): QuickbooksInvoice
    {
        return $this->quickbooksInvoiceRepository->updateOrCreate($invoice);
    }

    public function getInvoicesLastUpdatedTime(): ?string
    {
        return $this->quickbooksInvoiceRepository->getLastUpdatedTime();
    }

    /**
     * This function get all Invoices from QuickBooks then store them in the database.
     *
     * @throws ServiceException
     * @throws SdkException
     */
    public function syncInvoicesFromQuickbooks(string $whereClause = null): array
    {
        // Increase the max execution time of quickbooks
        ini_set('max_execution_time', 3600);

        $dataService = $this->quickbooksEntityService->configureDataService();

        $startPosition = 1;
        $invoices = $dataService->Query("Select * from Invoice $whereClause ORDERBY Id", $startPosition);

        $newInvoices = [];

        while ($invoices !== null) {
            foreach ($invoices as $invoice) {
                $newInvoices[] = $this->updateOrCreateInvoice($invoice);
            }

            $startPosition += 100;
            $invoices = $dataService->Query("Select * from Invoice $whereClause ORDERBY Id", $startPosition);
        }

        return $newInvoices;
    }
}
