<?php

use Livewire\Volt\Volt;
use App\Livewire\Courses\CourseList;
use App\Livewire\Courses\EditCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Courses\CourseDetails;
use App\Livewire\Courses\CreateCourses;
use App\Livewire\Dashboard\UserDashboard;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\StudentDashboard;
use App\Http\Controllers\Api\CourseController;
use App\Livewire\Dashboard\InstructorDashboard;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ana Sayfa ve Genel Rotalar
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Kimlik Doğrulama Rotaları (auth.php'den gelenlerle birlikte)
require __DIR__.'/auth.php';

// Authenticated Kullanıcı Rotaları
Route::middleware(['auth', 'verified'])->group(function () {
    // Ana Dashboard (Rol Bazlı Yönlendirme)
    Route::get('/dashboard', function () {
        $user = Auth::user(); // auth() yerine Auth facade

        return match($user->role) {
            'student' => app(StudentDashboard::class)->render(),
            'instructor' => app(InstructorDashboard::class)->render(),
            'admin' => app(AdminDashboard::class)->render(),
            default => redirect('/'),
        };
    })->name('dashboard');

    // Ayarlar
    Route::prefix('settings')->group(function () {
        Route::redirect('/', 'settings/profile');
        Volt::route('profile', 'settings.profile')->name('settings.profile');
        Volt::route('password', 'settings.password')->name('settings.password');
        Volt::route('appearance', 'settings.appearance')->name('settings.appearance');

        // Eğitmen ve Admin için ek ayarlar
        Route::middleware('role:instructor,admin')->group(function () {
            Volt::route('notifications', 'settings.notifications')->name('settings.notifications');
        });
    });

    // Kurs İşlemleri
    Route::prefix('courses')->group(function () {
        Volt::route('/', 'courses.index')->name('courses.index');
        // Volt::route('/{course}', 'courses.show')->name('courses.show');

        // Kayıt işlemleri
        Route::post('/{course}/enroll', [CourseController::class, 'enroll'])
            ->name('courses.enroll');
        Route::post('/{course}/unenroll', [CourseController::class, 'unenroll'])
            ->name('courses.unenroll');
    });

    // Rol Bazlı Özel Rotalar
    Route::middleware('role:instructor')->group(function () {
        Volt::route('/instructor/courses', 'instructor.courses.index')->name('instructor.courses');
        Volt::route('/instructor/earnings', 'instructor.earnings')->name('instructor.earnings');
    });

    Route::middleware('role:admin')->group(function () {
        Volt::route('/admin/users', 'admin.users.index')->name('admin.users');
        Volt::route('/admin/courses', 'admin.courses.index')->name('admin.courses');
        Volt::route('/admin/settings', 'admin.settings')->name('admin.settings');
    });
});

Route::get('/test-dashboard', function() {
   $service = app(\App\Services\DashboardService::class);

    // Tüm repository'leri tek tek test edin
    dd([
        'users' => app(\App\Interfaces\UserRepositoryInterface::class)->countUsers(),
        'courses' => app(\App\Interfaces\CourseRepositoryInterface::class)->countAllCourses(),
        'enrollments' => app(\App\Interfaces\EnrollmentRepositoryInterface::class)->countActiveEnrollments(),
        'revenue' => app(\App\Interfaces\EnrollmentRepositoryInterface::class)->calculateMonthlyRevenue(),
        'service_output' => $service->getSystemStats()
    ]);
});



// Kurs Oluşturma Rotaları





//   Route::middleware(['auth', 'verified'])->group(function () {
//       Route::get('/courses/create', function () {
//           $user = Auth::user(); // auth() yerine Auth facade

//           return match($user->role) {
//               'instructor' => app(CreateCourses::class)->render(),
//               'admin' => app(CreateCourses::class)->render(),
//               default => redirect('/'),
//           };
//       })->name('courses.create');
//   });



Route::middleware(['auth', 'verified', 'role.admin.instructor'])->group(function () {
    Route::get('/courses/create', CreateCourses::class)->name('courses.create');
});
Route::get('/{course}/edit', EditCourse::class)
    ->middleware('can:update,course')
    ->name('courses.edit');

// Route::middleware(['auth', 'verified'])->get('/courses/aa', CreateCourses::class)->name('courses.create');














//normal kullacılar için kurs listesi
Route::get('/courses', CourseList::class)->name('courses.index');

Route::get('/courses/{id}', CourseDetails::class)->name('courses.show');
