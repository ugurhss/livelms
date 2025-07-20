<?php

// app/Livewire/Result.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QuizAttempt;
use App\Services\QuizService;
use Illuminate\Support\Facades\Auth;

class Result extends Component
{
    public $attemptId;
    public $courseId;
    public $quizId;
    public $result;
    public $loading = true;

    public function mount($attemptId, $courseId, $quizId, QuizService $quizService)
    {
        $this->attemptId = $attemptId;
        $this->courseId = $courseId;
        $this->quizId = $quizId;

        try {
            $this->result = $quizService->getQuizAttempt($attemptId);

            // Yetki kontrolü
            if ($this->result->user_id != Auth()->id() || $this->result->quiz_id != $quizId) {
                abort(403, 'Bu sonucu görüntüleme yetkiniz yok.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Sonuç bulunamadı: ' . $e->getMessage());
            $this->redirect(route('courses.quizzes.index', $courseId));
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.quiz.result');
    }
}
