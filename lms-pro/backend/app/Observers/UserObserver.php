<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Observer for the User model.
 *
 * Handles model events for User, such as creating, updating, and deleting.
 */
class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * This is triggered just before a new user is saved to the database.
     * We use this to ensure the password is always hashed.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user): void
    {
        if ($user->isDirty('password')) {
            $user->password = Hash::make($user->password);
        }
    }

    /**
     * Handle the User "updating" event.
     *
     * This is triggered just before an existing user is saved.
     * We check if the password attribute has been changed and, if so, hash the new password.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user): void
    {
        if ($user->isDirty('password')) {
            $user->password = Hash::make($user->password);
        }
    }

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user): void
    {
        Log::info("New user created: {$user->name} (ID: {$user->id})");
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user): void
    {
        Log::warning("User deleted: {$user->name} (ID: {$user->id})");
        // Here you could also add logic to clean up related data if needed.
    }
}