<?php

use Livewire\Volt\Volt;

use App\Livewire\Quiz\CreateQuiz;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Api\CourseController;
use App\Livewire\Quiz\QuestionsCreate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Örnek HTTPS URL'ler:
| - Ana Sayfa: https://siteadi.com/
| - Giriş Sayfası: https://siteadi.com/login
| - Kayıt Sayfası: https://siteadi.com/register
|
*/

// Ana Sayfa ve Genel Rotalar
Route::get('/', function () {
    return view('welcome');
})->name('home'); // Örnek URL: https://siteadi.com/

// Kimlik Doğrulama Rotaları
require __DIR__.'/auth.php'; // Örnek URL'ler:
// - https://siteadi.com/login
// - https://siteadi.com/register
// - https://siteadi.com/forgot-password

// Authenticated Kullanıcı Rotaları
/*
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); // Örnek URL: https://siteadi.com/dashboard

    // Ayarlar
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::redirect('/', 'settings/profile'); // Örnek URL: https://siteadi.com/settings → /settings/profile'e yönlendirir

        // Profil Ayarları
        Volt::route('profile', 'settings.profile')->name('profile'); // Örnek URL: https://siteadi.com/settings/profile

        // Şifre Değiştirme
        Volt::route('password', 'settings.password')->name('password'); // Örnek URL: https://siteadi.com/settings/password

        // Görünüm Ayarları
        Volt::route('appearance', 'settings.appearance')->name('appearance'); // Örnek URL: https://siteadi.com/settings/appearance
    });

    // Kurs İşlemleri


    // Quiz İşlemleri
    Route::prefix('courses/{courseId}/quizzes')->name('courses.quizzes.')->group(function () {
        // Quiz Listesi
        Route::get('/', [QuizController::class, 'index'])->name('index'); // Örnek URL: https://siteadi.com/courses/1/quizzes

        // Quiz Oluşturma Formu
        Route::get('/create', [QuizController::class, 'create'])
            ->middleware('role.admin.instructor')
            ->name('create'); // Örnek URL: https://siteadi.com/courses/1/quizzes/create

        // Quiz Oluşturma (POST)
        Route::post('/', [QuizController::class, 'store'])
            ->middleware('role.admin.instructor')
            ->name('store'); // Örnek POST URL: https://siteadi.com/courses/1/quizzes

        // Quiz Detayı
        Route::get('/{quizId}', [QuizController::class, 'show'])->name('show'); // Örnek URL: https://siteadi.com/courses/1/quizzes/1

        // Quiz Düzenleme
        Route::get('/{quizId}/edit', [QuizController::class, 'edit'])
            ->middleware('role.admin.instructor')
            ->name('edit'); // Örnek URL: https://siteadi.com/courses/1/quizzes/1/edit

        // Quiz Güncelleme (PUT)
        Route::put('/{quizId}', [QuizController::class, 'update'])
            ->middleware('role.admin.instructor')
            ->name('update'); // Örnek PUT URL: https://siteadi.com/courses/1/quizzes/1

        // Quiz Silme
        Route::delete('/{quizId}', [QuizController::class, 'destroy'])
            ->middleware('role.admin.instructor')
            ->name('destroy'); // Örnek DELETE URL: https://siteadi.com/courses/1/quizzes/1

        // Quiz Sorusu Ekleme
        Route::get('/{quizId}/questions/create', [QuizController::class, 'createQuestion'])
            ->middleware('role.admin.instructor')
            ->name('questions.create'); // Örnek URL: https://siteadi.com/courses/1/quizzes/1/questions/create

        // Quiz Sorusu Kaydetme
        Route::post('/{quizId}/questions', [QuizController::class, 'storeQuestion'])
            ->middleware('role.admin.instructor')
            ->name('questions.store'); // Örnek POST URL: https://siteadi.com/courses/1/quizzes/1/questions

        // Quiz Başlatma (Öğrenci)
        Route::get('/{quizId}/start', [QuizController::class, 'startQuiz'])->name('start'); // Örnek URL: https://siteadi.com/courses/1/quizzes/1/start

        // Quiz Gönderme (Öğrenci)
        Route::post('/{quizId}/submit', [QuizController::class, 'submitQuiz'])->name('submit'); // Örnek POST URL: https://siteadi.com/courses/1/quizzes/1/submit

        // Quiz Sonucu
        Route::get('/{quizId}/results/{attempt}', [QuizController::class, 'showResult'])->name('result'); // Örnek URL: https://siteadi.com/courses/1/quizzes/1/results/1
    });
});

// Rol Bazlı Dashboard Rotaları
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Paneli
    Route::prefix('admin')->name('admin.')->middleware('role.admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Örnek URL: https://siteadi.com/admin/dashboard
    });

    // Eğitmen Paneli
    Route::prefix('instructor')->name('instructor.')->middleware('role.admin.instructor')->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard'); // Örnek URL: https://siteadi.com/instructor/dashboard
    });

    // Öğrenci Paneli
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard'); // Örnek URL: https://siteadi.com/student/dashboard
    });
});

// Genel Kurs Rotaları (Auth gerekmez)
// Route::get('/courses', [CourseController::class, 'getPublishedCourses'])->name('courses.public.index');
// Route::get('/courses/{id}', [CourseController::class,'show'])->name('courses.public.show'); // Örnek URL: https://siteadi.com/courses/1

// Test Route'u (Geliştirme)
Route::get('/test-dashboard', function() {
    // Test verilerini gösterir
    $service = app(\App\Services\DashboardService::class);
    dd([
        'users' => app(\App\Interfaces\UserRepositoryInterface::class)->countUsers(),
        'courses' => app(\App\Interfaces\CourseRepositoryInterface::class)->countAllCourses(),
        'enrollments' => app(\App\Interfaces\EnrollmentRepositoryInterface::class)->countActiveEnrollments(),
        'revenue' => app(\App\Interfaces\EnrollmentRepositoryInterface::class)->calculateMonthlyRevenue(),
        'service_output' => $service->getSystemStats()
    ]);

})->middleware('auth'); // Örnek URL: https://siteadi.com/test-dashboard


/* calışanlar
 */
/*

  Route::prefix('courses')->name('courses.')->group(function () {
  // Kurs yönetimi
    Route::resource('courses', CourseController::class)->except(['index', 'show']);


Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])
    ->name('courses.edit');
Route::put('/courses/{course}', [CourseController::class, 'update'])
    ->name('courses.update');

    // Eğitmen kursları dashboard at
    Route::get('/instructor/courses', [CourseController::class, 'instructorCourses'])
        ->name('courses.instructor');

    // Kurs kayıt işlemleri
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])
        ->name('courses.enroll');
    Route::post('/courses/{course}/unenroll', [CourseController::class, 'unenroll'])
        ->name('courses.unenroll');

    // Kullanıcının kayıtlı kursları
    Route::get('/my-courses', [CourseController::class, 'myCourses'])
        ->name('courses.my-courses');

    // Sınav
    Route::get('/courses/{course}/quiz', [CourseController::class, 'quiz'])
        ->name('courses.quiz');
});
Route::resource('courses', CourseController::class)->only(['index', 'show']);

*/
Route::middleware(['auth'])->group(function () {
    // Course creation and management
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    // Course editing
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Enrollment routes
    Route::post('/courses/{courseId}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{courseId}/unenroll', [CourseController::class, 'unenroll'])->name('courses.unenroll');

    // Instructor dashboard
    Route::get('/instructor/courses', [CourseController::class, 'instructorCourses'])->name('instructor.courses');

    // User's enrolled courses
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('my-courses');

    // Course quiz
});

// Public course routes
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');



//quiz

Route::get('/courses/{course}/quizzes/create', [CourseController::class, 'createQuiz'])->name('courses.quizzes.create');

Route::get('/a', [CourseController::class, 'index2'])->name('courses.index');
// Route::get('/courses/{course}/quizzes/create', CreateQuiz::class)->name('quizzes.create');
Route::get('/quizzes/{quiz}/questions', QuestionsCreate::class)->name('quizzes.questions');
