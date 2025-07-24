<?php

use Livewire\Volt\Volt;
use App\Livewire\Courses\CourseList;
use App\Livewire\Courses\EditCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Courses\CourseDetails;
use App\Livewire\Courses\CreateCourses;
use App\Http\Controllers\QuizController;
use App\Livewire\Dashboard\UserDashboard;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\StudentDashboard;
use App\Http\Controllers\Api\CourseController;
use App\Livewire\Dashboard\InstructorDashboard;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\InstructorDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ana Sayfa
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
require __DIR__.'/auth.php';

// Authenticated kullanıcılar için
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Ayarlar
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::redirect('/', 'settings/profile');

        Volt::route('profile', 'settings.profile')->name('profile');
        Volt::route('password', 'settings.password')->name('password');
        Volt::route('appearance', 'settings.appearance')->name('appearance');
    });

    // Quiz işlemleri
    Route::prefix('courses/{courseId}/quizzes')->name('courses.quizzes.')->group(function () {
        Route::get('/', [QuizController::class, 'index'])->name('index');
        Route::get('/create', [QuizController::class, 'create'])->middleware('role.admin.instructor')->name('create');
        Route::post('/', [QuizController::class, 'store'])->middleware('role.admin.instructor')->name('store');
        Route::get('/{quizId}', [QuizController::class, 'show'])->name('show');
        Route::get('/{quizId}/edit', [QuizController::class, 'edit'])->middleware('role.admin.instructor')->name('edit');
        Route::put('/{quizId}', [QuizController::class, 'update'])->middleware('role.admin.instructor')->name('update');
        Route::delete('/{quizId}', [QuizController::class, 'destroy'])->middleware('role.admin.instructor')->name('destroy');
        Route::get('/{quizId}/questions/create', [QuizController::class, 'createQuestion'])->middleware('role.admin.instructor')->name('questions.create');
        Route::post('/{quizId}/questions', [QuizController::class, 'storeQuestion'])->middleware('role.admin.instructor')->name('questions.store');
        Route::get('/{quizId}/start', [QuizController::class, 'startQuiz'])->name('start');
        Route::post('/{quizId}/submit', [QuizController::class, 'submitQuiz'])->name('submit');
        Route::get('/{quizId}/results/{attempt}', [QuizController::class, 'showResult'])->name('result');
    });
});

// Rol Bazlı Dashboard Rotaları
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('role.admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('instructor')->name('instructor.')->middleware('role.admin.instructor')->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    });
});

// Kurs İşlemleri (Elle tanımlandı)
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/create', [CourseController::class, 'create'])->name('create');
    Route::post('/', [CourseController::class, 'store'])->name('store');
    Route::get('/{course}', [CourseController::class, 'show'])->name('show');
    Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
    Route::put('/{course}', [CourseController::class, 'update'])->name('update');
    Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
    Route::get('/instructor/courses', [CourseController::class, 'instructorCourses'])->name('instructor');
    Route::post('/{course}/enroll', [CourseController::class, 'enroll'])->name('enroll');
    Route::post('/{course}/unenroll', [CourseController::class, 'unenroll'])->name('unenroll');
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('my-courses');
});

/*
|--------------------------------------------------------------------------
| NOT: Aşağıdaki satır route çakışmalarına neden oluyordu.
| Elle tanımladığınız route'lar bu işi zaten yapıyor.
|--------------------------------------------------------------------------
*/

// Route::resource('courses', CourseController::class)->only(['index', 'show']);


// Test Dashboard (isteğe bağlı)
Route::get('/test-dashboard', function() {
    $service = app(\App\Services\DashboardService::class);
    dd([
        'users' => app(\App\Interfaces\UserRepositoryInterface::class)->countUsers(),
        'courses' => app(\App\Interfaces\CourseRepositoryInterface::class)->countAllCourses(),
        'enrollments' => app(\App\Interfaces\EnrollmentRepositoryInterface::class)->countActiveEnrollments(),
        'revenue' => app(\App\Interfaces\EnrollmentRepositoryInterface::class)->calculateMonthlyRevenue(),
        'service_output' => $service->getSystemStats()
    ]);
})->middleware('auth');
