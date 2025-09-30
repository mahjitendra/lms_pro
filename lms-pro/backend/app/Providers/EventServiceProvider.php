<?php

namespace App\Providers;

use App\Events\AI\ModelTrainingCompleted;
use App\Events\AI\PredictionGenerated;
use App\Events\AI\VideoProcessed;
use App\Events\User\UserEnrolled;
use App\Events\User\UserRegistered;
use App\Listeners\AI\HandleModelTrainingCompleted;
use App\Listeners\AI\HandlePredictionGenerated;
use App\Listeners\User\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Laravel's default Registered event
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Our custom UserRegistered event
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],

        // Our custom UserEnrolled event
        UserEnrolled::class => [
            // Example: \App\Listeners\Course\DispatchCertificateGeneration::class,
            // Example: \App\Listeners\User\UpdateLearningPath::class,
        ],

        // --- AI Events ---
        ModelTrainingCompleted::class => [
            HandleModelTrainingCompleted::class,
        ],
        PredictionGenerated::class => [
            HandlePredictionGenerated::class,
        ],
        VideoProcessed::class => [
            // Example: \App\Listeners\AI\UpdateVideoMetadata::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}