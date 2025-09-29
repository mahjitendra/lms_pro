<?php

namespace App\Console\Commands\AI;

use App\Jobs\AI\GenerateRecommendationsJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateRecommendationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:generate-recommendations {user_id?} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job to generate course recommendations for a specific user or all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $allUsers = $this->option('all');

        if (!$userId && !$allUsers) {
            $this->error('You must specify a user_id or use the --all option.');
            return 1;
        }

        if ($allUsers) {
            $this->info('Dispatching recommendation jobs for all users...');
            $users = User::where('is_active', true)->get(); // Example condition
            foreach ($users as $user) {
                $this->dispatchJob($user);
            }
            $this->info('All recommendation jobs have been dispatched.');
        } else {
            try {
                $user = User::findOrFail($userId);
                $this->info("Dispatching recommendation job for user: {$user->name} (ID: {$user->id})");
                $this->dispatchJob($user);
                $this->info('Recommendation job dispatched successfully.');
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $this->error("Error: User with ID {$userId} not found.");
                Log::error("GenerateRecommendationsCommand: User not found.", ['user_id' => $userId, 'error' => $e->getMessage()]);
                return 1;
            }
        }

        return 0;
    }

    /**
     * Dispatches the job for a given user.
     *
     * @param User $user
     */
    private function dispatchJob(User $user)
    {
        try {
            GenerateRecommendationsJob::dispatch($user);
            Log::info("Dispatched GenerateRecommendationsJob for User ID: {$user->id}");
        } catch (\Exception $e) {
            $this->error("Failed to dispatch job for user ID {$user->id}: " . $e->getMessage());
            Log::error("GenerateRecommendationsCommand: Failed to dispatch job.", ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
    }
}