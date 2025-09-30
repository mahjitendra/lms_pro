<?php

namespace App\Providers;

use App\Models\Course\Course;
use App\Models\Course\Enrollment;
use App\Models\User;
use App\Observers\CourseObserver;
use App\Observers\EnrollmentObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register our custom service providers
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(AIServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This is the perfect place to register our model observers.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Course::observe(CourseObserver::class);
        Enrollment::observe(EnrollmentObserver::class);
    }
}