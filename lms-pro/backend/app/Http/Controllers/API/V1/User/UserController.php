<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest; // To be created
use App\Http\Requests\User\UpdateUserRequest; // To be created
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // Protect all methods in this controller with authorization middleware
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return ResponseHelper::success($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());
        return ResponseHelper::success($user, 'User created successfully.', 201);
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return ResponseHelper::success($user->load('roles', 'permissions'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());
        return ResponseHelper::success($updatedUser, 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);
        return ResponseHelper::success(null, 'User deleted successfully.', 204);
    }
}