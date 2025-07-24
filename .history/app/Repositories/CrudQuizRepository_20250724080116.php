<?php

namespace App\Repositories;

use App\Interfaces\CrudQuizRepositoryInterface;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Carbon\Carbon;

class CrudQuizRepository implements CrudQuizRepositoryInterface
{
     public function getAllQuizzes()
    {
        return Quiz::with('course')->get();
    }

    public function getQuizById($quizId)
    {
        return Quiz::with(['questions.answers'])->findOrFail($quizId);
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
        return Question::where('quiz_id', $quizId)->with('answers')->get();
    }

    public function createQuestion($quizId, array $questionDetails)
    {
        $questionDetails['quiz_id'] = $quizId;
        return Question::create($questionDetails);
    }

    public function updateQuestion($questionId, array $newDetails)
    {
        return Question::whereId($questionId)->update($newDetails);
    }

    public function deleteQuestion($questionId)
    {
        return Question::destroy($questionId);
    }

    public function getQuestionWithAnswers($questionId)
    {
        return Question::with('answers')->findOrFail($questionId);
    }
}
