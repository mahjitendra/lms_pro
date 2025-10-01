<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRoleRequest; // To be created
use App\Models\User\Role;
use App\Services\User\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        // In a real app, every method here would be protected by authorization middleware
        // e.g., $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the roles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $roles = $this->roleService->getAllRoles();
        return ResponseHelper::success($roles);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());
        return ResponseHelper::success($role, 'Role created successfully.', 201);
    }

    /**
     * Display the specified role with its permissions.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        return ResponseHelper::success($role->load('permissions'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param StoreRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(StoreRoleRequest $request, Role $role): JsonResponse
    {
        $updatedRole = $this->roleService->updateRole($role, $request->validated());
        return ResponseHelper::success($updatedRole, 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->deleteRole($role);
        return ResponseHelper::success(null, 'Role deleted successfully.', 204);
    }

    /**
     * Assign permissions to a role.
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate(['permissions' => 'required|array']);
        $this->roleService->assignPermissionsToRole($role, $validated['permissions']);
        return ResponseHelper::success($role->load('permissions'), 'Permissions assigned successfully.');
    }
}