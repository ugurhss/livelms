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
    public function getAll()
    {
        return Quiz::with('questions.answers')->get();
    }

    public function getById($id)
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

    public function getByCourseId($courseId)
    {
        return Quiz::where('course_id', $courseId)->get();
    }

    public function publish($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update(['is_published' => true]);
        return $quiz;
    }

    public function unpublish($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update(['is_published' => false]);
        return $quiz;
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
