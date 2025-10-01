<?php

namespace App\Http\Controllers\API\V1\Assessment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Assessment\Certificate;
use App\Services\Assessment\CertificateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display a listing of the authenticated user's certificates.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $certificates = $this->certificateService->getCertificatesForUser(Auth::user());
        return ResponseHelper::success($certificates, 'Certificates retrieved successfully.');
    }

    /**
     * Display the specified certificate.
     *
     * @param Certificate $certificate
     * @return JsonResponse
     */
    public function show(Certificate $certificate): JsonResponse
    {
        // Ensure the user is authorized to view this certificate
        $this->authorize('view', $certificate);

        return ResponseHelper::success($certificate->load('user', 'course'));
    }

    /**
     * Provide a secure download for the certificate PDF.
     *
     * @param Certificate $certificate
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Certificate $certificate)
    {
        $this->authorize('view', $certificate);

        try {
            return $this->certificateService->downloadCertificate($certificate);
        } catch (\Exception $e) {
            // In a real app, you'd redirect back with an error.
            // For an API, returning a JSON error is better.
            return ResponseHelper::notFound('Certificate file not found.');
        }
    }

    /**
     * Verify a certificate's authenticity (e.g., via a public page).
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function verify(string $uuid): JsonResponse
    {
        $certificate = $this->certificateService->verifyCertificateByUuid($uuid);

        if ($certificate) {
            return ResponseHelper::success($certificate, 'Certificate is valid.');
        }

        return ResponseHelper::notFound('Certificate not found or is invalid.');
    }
}