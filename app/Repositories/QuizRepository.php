<?php

namespace App\Repositories;

use App\Interfaces\QuizRepositoryInterface;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Carbon\Carbon;

class QuizRepository implements QuizRepositoryInterface
{
    public function getQuizByCourse($courseId)
    {
        return Quiz::where('course_id', $courseId)->with('questions.answers')->get();
    }

    public function getQuizById($quizId)
    {
        return Quiz::with(['questions' => function($query) {
            $query->with(['answers' => function($q) {
                $q->select('id', 'question_id', 'answer_text');
            }]);
        }])->findOrFail($quizId);
    }

    public function createQuiz(array $quizDetails)
    {
        return Quiz::create($quizDetails);
    }
public function updateQuiz($quizId, array $newDetails)
{
    $quiz = Quiz::findOrFail($quizId);
    $quiz->update($newDetails);
    return $quiz; // Güncellenmiş quiz nesnesini döndür
}

    public function deleteQuiz($quizId)
    {
        return Quiz::destroy($quizId);
    }

    public function getQuestions($quizId)
    {
        return Question::where('quiz_id', $quizId)->with('answers')->get();
    }

    public function createQuestion($quizId, array $questionDetails)
    {
        $question = Question::create([
            'quiz_id' => $quizId,
            'question_text' => $questionDetails['question_text'],
            'question_type' => $questionDetails['question_type'],
            'points' => $questionDetails['points'] ?? 1,
        ]);

        foreach ($questionDetails['answers'] as $answer) {
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answer['answer_text'],
                'is_correct' => $answer['is_correct'],
            ]);
        }

        return $question->load('answers');
    }

    public function submitQuizAttempt($quizId, $userId, array $answers)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questions = $this->getQuestions($quizId);

        // Check if quiz is available
        $availability = $this->checkQuizAvailability($quizId);
        if (!$availability['available']) {
            throw new \Exception($availability['message']);
        }

        // Start attempt
        $attempt = QuizAttempt::create([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'started_at' => now(),
        ]);

        $score = 0;
        $totalQuestions = $questions->count();

        foreach ($answers as $questionId => $userAnswer) {
            $question = $questions->firstWhere('id', $questionId);

            if (!$question) continue;

            $isCorrect = false;
            $answerId = null;
            $answerText = null;

            if ($question->question_type === 'true_false' || $question->question_type === 'multiple_choice') {
                $correctAnswer = $question->answers->firstWhere('is_correct', true);
                $isCorrect = $userAnswer == $correctAnswer->id;
                $answerId = $userAnswer;
            } else {
                // For short answer questions, you might need more complex checking
                $answerText = $userAnswer;
                // This is a simple implementation - you might want to implement more complex matching
                $correctAnswer = $question->answers->first();
                $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer->answer_text));
            }

            if ($isCorrect) {
                $score += $question->points;
            }

            UserAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'answer_id' => $answerId,
                'answer_text' => $answerText,
                'is_correct' => $isCorrect,
            ]);
        }

        // Calculate percentage score
        $totalPossibleScore = $questions->sum('points');
        $percentageScore = $totalPossibleScore > 0 ? ($score / $totalPossibleScore) * 100 : 0;

        // Update attempt
        $attempt->update([
            'completed_at' => now(),
            'score' => $percentageScore,
            'is_passed' => $percentageScore >= $quiz->passing_score,
        ]);

        return $attempt;
    }

    public function getQuizResults($quizId, $userId)
    {
        return QuizAttempt::with(['userAnswers' => function($query) {
            $query->with('question', 'answer');
        }])
        ->where('quiz_id', $quizId)
        ->where('user_id', $userId)
        ->latest()
        ->first();
    }

    public function checkQuizAvailability($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $now = Carbon::now();

        if (!$quiz->is_published) {
            return ['available' => false, 'message' => 'This quiz is not currently available.'];
        }

        if ($quiz->start_date && $now->lt($quiz->start_date)) {
            return ['available' => false, 'message' => 'This quiz will be available from ' . $quiz->start_date->format('Y-m-d H:i')];
        }

        if ($quiz->end_date && $now->gt($quiz->end_date)) {
            return ['available' => false, 'message' => 'This quiz was closed on ' . $quiz->end_date->format('Y-m-d H:i')];
        }

        return ['available' => true, 'message' => 'Quiz is available'];
    }
public function getQuizAttemptById($attemptId)
{
    return QuizAttempt::with([
        'quiz',
        'userAnswers' => function($query) {
            $query->with(['question', 'answer']);
        }
    ])->findOrFail($attemptId);
}
}
