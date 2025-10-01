<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Payment\Invoice;
use App\Services\Payment\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the authenticated user's invoices.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $invoices = $this->invoiceService->getInvoicesForUser(Auth::user());
        return ResponseHelper::success($invoices);
    }

    /**
     * Display the specified invoice.
     *
     * @param Invoice $invoice
     * @return JsonResponse
     */
    public function show(Invoice $invoice): JsonResponse
    {
        // Ensure the user is authorized to view this invoice
        $this->authorize('view', $invoice);

        return ResponseHelper::success($invoice);
    }

    /**
     * Download the specified invoice as a PDF.
     *
     * @param Invoice $invoice
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        try {
            // The service will handle generating the PDF from the invoice data
            return $this->invoiceService->downloadInvoicePdf($invoice);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Could not generate invoice PDF: ' . $e->getMessage());
        }
    }
}