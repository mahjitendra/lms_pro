<?php

namespace App\Http\Controllers\API\V1\Content;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\StoreResourceRequest; // To be created
use App\Models\Content\Resource;
use App\Models\Course\Course;
use App\Services\Content\ResourceService;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    protected $resourceService;

    public function __construct(ResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    /**
     * Display a listing of the resources for a specific course.
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function index(Course $course): JsonResponse
    {
        $resources = $this->resourceService->getResourcesForCourse($course);
        return ResponseHelper::success($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResourceRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function store(StoreResourceRequest $request, Course $course): JsonResponse
    {
        try {
            $resource = $this->resourceService->createResource($course, $request->validated(), $request->file('file'));
            return ResponseHelper::success($resource, 'Resource created successfully.', 201);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to create resource: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function show(Resource $resource): JsonResponse
    {
        return ResponseHelper::success($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreResourceRequest $request
     * @param Resource $resource
     * @return JsonResponse
     */
    public function update(StoreResourceRequest $request, Resource $resource): JsonResponse
    {
        $updatedResource = $this->resourceService->updateResource($resource, $request->validated());
        return ResponseHelper::success($updatedResource, 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function destroy(Resource $resource): JsonResponse
    {
        $this->resourceService->deleteResource($resource);
        return ResponseHelper::success(null, 'Resource deleted successfully.', 204);
    }
}