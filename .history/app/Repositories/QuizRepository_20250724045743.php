<?php

namespace App\Repositories;

use App\Interfaces\QuizRepositoryInterface;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuizRepository implements QuizRepositoryInterface
{
    public function getQuizByCourse($courseId)
    {
        return Quiz::where('course_id', $courseId)
            ->with('questions.answers')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getQuizById($quizId)
    {
        return Quiz::with(['questions' => function($query) {
            $query->with(['answers' => function($q) {
                $q->select('id', 'question_id', 'answer_text', 'is_correct');
            }]);
        }])->findOrFail($quizId);
    }

    public function createQuiz(array $quizDetails)
    {
        return Quiz::create($quizDetails);
    }

    public function updateQuiz($quizId, array $newDetails)
    {
        return Quiz::whereId($quizId)->update($newDetails);
    }

    public function deleteQuiz($quizId)
    {
        return Quiz::destroy($quizId);
    }

    public function getQuestions($quizId)
    {
        return Question::where('quiz_id', $quizId)
            ->with('answers')
            ->get();
    }

    public function createQuestion($quizId, array $questionDetails)
    {
        return DB::transaction(function () use ($quizId, $questionDetails) {
            $question = Question::create([
                'quiz_id' => $quizId,
                'question_text' => $questionDetails['question_text'],
                'question_type' => $questionDetails['question_type'],
                'points' => $questionDetails['points'],
            ]);

            if (isset($questionDetails['answers'])) {
                foreach ($questionDetails['answers'] as $answer) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $answer['answer_text'],
                        'is_correct' => $answer['is_correct'],
                    ]);
                }
            }

            return $question->load('answers');
        });
    }

    public function submitQuizAttempt($quizId, $userId, array $answers)
    {
        return DB::transaction(function () use ($quizId, $userId, $answers) {
            $quiz = Quiz::findOrFail($quizId);
            $questions = $this->getQuestions($quizId);

            $attempt = QuizAttempt::create([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'started_at' => now(),
            ]);

            $score = 0;
            $totalPossibleScore = $questions->sum('points');

            foreach ($answers as $questionId => $userAnswer) {
                $question = $questions->firstWhere('id', $questionId);
                if (!$question) continue;

                $isCorrect = $this->checkAnswerCorrectness($question, $userAnswer);

                if ($isCorrect) {
                    $score += $question->points;
                }

                $this->storeUserAnswer($attempt->id, $questionId, $userAnswer, $isCorrect);
            }

            $percentageScore = $totalPossibleScore > 0 ? ($score / $totalPossibleScore) * 100 : 0;

            $attempt->update([
                'completed_at' => now(),
                'score' => $percentageScore,
                'is_passed' => $percentageScore >= $quiz->passing_score,
            ]);

            return $attempt;
        });
    }

    protected function checkAnswerCorrectness($question, $userAnswer)
    {
        switch ($question->question_type) {
            case 'multiple_choice':
            case 'true_false':
                $correctAnswer = $question->answers->firstWhere('is_correct', true);
                return $userAnswer == $correctAnswer->id;

            case 'short_answer':
                $correctAnswer = $question->answers->first();
                return strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer->answer_text));

            default:
                return false;
        }
    }

    protected function storeUserAnswer($attemptId, $questionId, $userAnswer, $isCorrect)
    {
        $data = [
            'attempt_id' => $attemptId,
            'question_id' => $questionId,
            'is_correct' => $isCorrect,
        ];

        if (is_numeric($userAnswer)) {
            $data['answer_id'] = $userAnswer;
        } else {
            $data['answer_text'] = $userAnswer;
        }

        return UserAnswer::create($data);
    }

    public function getQuizResults($quizId, $userId)
    {
        return QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    public function checkQuizAvailability($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $now = Carbon::now();

        if (!$quiz->is_published) {
            return ['available' => false, 'message' => 'Bu quiz şu anda kullanılamıyor.'];
        }

        if ($quiz->start_date && $now->lt($quiz->start_date)) {
            return ['available' => false, 'message' => 'Bu quiz ' . $quiz->start_date->format('d.m.Y H:i') . ' tarihinde başlayacak.'];
        }

        if ($quiz->end_date && $now->gt($quiz->end_date)) {
            return ['available' => false, 'message' => 'Bu quiz ' . $quiz->end_date->format('d.m.Y H:i') . ' tarihinde sona erdi.'];
        }

        return ['available' => true, 'message' => 'Quiz kullanılabilir'];
    }

    public function getQuizAttemptById($attemptId)
    {
        return QuizAttempt::with([
            'quiz',
            'userAnswers.question',
            'userAnswers.answer'
        ])->findOrFail($attemptId);
    }
}
