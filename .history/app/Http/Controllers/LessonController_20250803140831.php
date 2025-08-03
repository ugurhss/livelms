<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseService;

class LessonController extends Controller
{



  protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }



    public function complete(Lesson $lesson)
{
    $userId = Auth::id();

    if (!$this->courseService->checkEnrollment($lesson->course_id, $userId)) {
        return redirect()->back()->with('error', 'Bu derse erişim izniniz yok');
    }

    $isCompleted = $lesson->completions()->where('user_id', $userId)->exists();

    if ($isCompleted) {
        $lesson->completions()->where('user_id', $userId)->delete();
        $message = 'Ders tamamlanmadı olarak işaretlendi';
    } else {
        $lesson->completions()->create(['user_id' => $userId]);
        $message = 'Ders başarıyla tamamlandı olarak işaretlendi';

        // Kurs ilerlemesini güncelle
        $this->courseService->updateUserProgress($lesson->course_id, $userId);
    }

    return redirect()->back()->with('success', $message);
}
}
