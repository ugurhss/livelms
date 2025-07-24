<?php

namespace App\Services;

use App\Interfaces\QuizRepositoryInterface;
use Carbon\Carbon;

class QuizService
{
   protected $quizRepository;

    public function __construct(QuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getAllQuizzes()
    {
        return $this->quizRepository->getAll();
    }

    public function getQuizById($id)
    {
        return $this->quizRepository->getById($id);
    }

    public function createQuiz(array $data)
    {
        return $this->quizRepository->create($data);
    }

    public function updateQuiz($id, array $data)
    {
        return $this->quizRepository->update($id, $data);
    }

    public function deleteQuiz($id)
    {
        return $this->quizRepository->delete($id);
    }

    public function getQuizzesByCourse($courseId)
    {
        return $this->quizRepository->getByCourseId($courseId);
    }

    public function publishQuiz($id)
    {
        return $this->quizRepository->publish($id);
    }

    public function unpublishQuiz($id)
    {
        return $this->quizRepository->unpublish($id);
    }

    public function getQuizQuestions($quizId)
    {
        return $this->quizRepository->getQuestions($quizId);
    }

    public function addQuestionToQuiz($quizId, array $questionData)
    {
        return $this->quizRepository->addQuestion($quizId, $questionData);
    }

    public function updateQuizQuestion($questionId, array $questionData)
    {
        return $this->quizRepository->updateQuestion($questionId, $questionData);
    }

    public function deleteQuizQuestion($questionId)
    {
        return $this->quizRepository->deleteQuestion($questionId);
    }

    public function getQuestionWithAnswers($questionId)
    {
        return $this->quizRepository->getQuestionWithAnswers($questionId);
    }
}
