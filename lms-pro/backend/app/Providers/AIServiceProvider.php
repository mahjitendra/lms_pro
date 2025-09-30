<?php

namespace App\Providers;

use App\Repositories\AI\AIModelRepository;
use App\Repositories\AI\DatasetRepository;
use App\Repositories\AI\PredictionRepository;
use App\Repositories\Contracts\AIModelRepositoryInterface;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use App\Repositories\Contracts\PredictionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the AIModelRepository
        $this->app->bind(
            AIModelRepositoryInterface::class,
            AIModelRepository::class
        );

        // Bind the PredictionRepository
        $this->app->bind(
            PredictionRepositoryInterface::class,
            PredictionRepository::class
        );

        // Bind the DatasetRepository
        $this->app->bind(
            DatasetRepositoryInterface::class,
            DatasetRepository::class
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