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
     public function getAll()
    {
        return Quiz::with('questions.answers')->get();
    }

    public function find($id)
    {
        return Quiz::with('questions.answers')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Quiz::create($data);
    }

    public function update($id, array $data)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update($data);
        return $quiz;
    }

    public function delete($id)
    {
        $quiz = Quiz::findOrFail($id);
        return $quiz->delete();
    }

    public function getQuestions($quizId)
    {
        return Question::where('quiz_id', $quizId)->with('answers')->get();
    }

    public function addQuestion($quizId, array $questionData)
    {
        $questionData['quiz_id'] = $quizId;
        return Question::create($questionData);
    }

    public function updateQuestion($questionId, array $questionData)
    {
        $question = Question::findOrFail($questionId);
        $question->update($questionData);
        return $question;
    }

    public function deleteQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        return $question->delete();
    }

    public function getQuestionWithAnswers($questionId)
    {
        return Question::with('answers')->findOrFail($questionId);
    }
}
