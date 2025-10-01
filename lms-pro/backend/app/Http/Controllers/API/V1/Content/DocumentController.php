<?php

namespace App\Http\Controllers\API\V1\Content;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\UploadDocumentRequest; // To be created
use App\Models\Content\Document;
use App\Services\Content\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display a listing of all documents.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $documents = $this->documentService->getAllDocuments($request->per_page ?? 20);
        return ResponseHelper::success($documents);
    }

    /**
     * Handle the upload of a new document.
     *
     * @param UploadDocumentRequest $request
     * @return JsonResponse
     */
    public function store(UploadDocumentRequest $request): JsonResponse
    {
        try {
            $documentFile = $request->file('document');
            $metadata = $request->validated();

            $document = $this->documentService->uploadDocument($documentFile, $metadata);

            return ResponseHelper::success($document, 'Document uploaded successfully.', 201);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to upload document: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified document resource.
     *
     * @param Document $document
     * @return JsonResponse
     */
    public function show(Document $document): JsonResponse
    {
        return ResponseHelper::success($document);
    }

    /**
     * Provide a download link for the specified document.
     * In a real app, this would need proper authorization.
     */
    public function download(Document $document)
    {
        return $this->documentService->downloadDocument($document);
    }

    /**
     * Update the metadata for a specified document.
     *
     * @param Request $request
     * @param Document $document
     * @return JsonResponse
     */
    public function update(Request $request, Document $document): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|nullable',
        ]);

        $updatedDocument = $this->documentService->updateDocument($document, $validatedData);
        return ResponseHelper::success($updatedDocument, 'Document metadata updated successfully.');
    }

    /**
     * Remove the specified document from storage.
     *
     * @param Document $document
     * @return JsonResponse
     */
    public function destroy(Document $document): JsonResponse
    {
        $this->documentService->deleteDocument($document);
        return ResponseHelper::success(null, 'Document deleted successfully.', 204);
    }
}