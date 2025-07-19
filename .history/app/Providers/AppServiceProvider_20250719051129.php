<?php

namespace App\Providers;

use App\Repositories\QuizRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Blade;
use App\Repositories\CourseRepository;
use App\Services\AdminDashboardService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\DashboardRepository;
use App\Services\StudentDashboardService;
use App\Repositories\EnrollmentRepository;
use App\Interfaces\QuizRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Services\InstructorDashboardService;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\DashboardRepositoryInterface;
use App\Interfaces\EnrollmentRepositoryInterface;
use App\Interfaces\AdminDashboardServiceInterface;
use App\Interfaces\StudentDashboardServiceInterface;
use App\Interfaces\InstructorDashboardServiceInterface;

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


         $this->app->bind(
            DashboardRepositoryInterface::class,
            DashboardRepository::class
        );

        // Service Bindings
        $this->app->bind(AdminDashboardServiceInterface::class, function ($app) {
            return new AdminDashboardService(
                $app->make(DashboardRepositoryInterface::class)
            );
        });

        $this->app->bind(InstructorDashboardServiceInterface::class, function ($app) {
            return new InstructorDashboardService(
                $app->make(DashboardRepositoryInterface::class)
            );
        });

        $this->app->bind(StudentDashboardServiceInterface::class, function ($app) {
            return new StudentDashboardService(
                $app->make(DashboardRepositoryInterface::class)
            );
        });
                $this->app->bind(QuizRepositoryInterface::class, QuizRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
          Blade::component('jetstream::components.dialog-modal', 'jet-dialog-modal');

    }
}
