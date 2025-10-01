<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\User\PermissionService;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        // In a real app, this controller would be protected by admin-only middleware.
        // $this->middleware('role:admin');
    }

    /**
     * Display a listing of all available permissions.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $permissions = $this->permissionService->getAllPermissions();
            return ResponseHelper::success($permissions, 'Permissions retrieved successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Could not retrieve permissions: ' . $e->getMessage());
        }
    }
}