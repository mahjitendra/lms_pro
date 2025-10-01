<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest; // To be created
use App\Services\User\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display the authenticated user's profile.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();
        $profile = $this->profileService->getProfileForUser($user);

        return ResponseHelper::success($profile);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $profileData = $request->validated();
            $avatarFile = $request->file('avatar');

            $updatedProfile = $this->profileService->updateProfile($user, $profileData, $avatarFile);

            return ResponseHelper::success($updatedProfile, 'Profile updated successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to update profile: ' . $e->getMessage());
        }
    }
}