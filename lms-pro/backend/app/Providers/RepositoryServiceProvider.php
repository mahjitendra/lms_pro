<?php

namespace App\Providers;

use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\EnrollmentRepository;
use App\Repositories\Course\LessonRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the CourseRepository
        $this->app->bind(
            CourseRepositoryInterface::class,
            CourseRepository::class
        );

        // Bind the LessonRepository
        $this->app->bind(
            LessonRepositoryInterface::class,
            LessonRepository::class
        );

        // Bind the EnrollmentRepository
        $this->app->bind(
            EnrollmentRepositoryInterface::class,
            EnrollmentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}