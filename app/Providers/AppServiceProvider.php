<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EnrollmentRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\EnrollmentRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
$this->app->bind(
            CourseRepositoryInterface::class,
            CourseRepository::class
        );

        $this->app->bind(
            CourseRepositoryInterface::class,
            CourseRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            EnrollmentRepositoryInterface::class,
            EnrollmentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
