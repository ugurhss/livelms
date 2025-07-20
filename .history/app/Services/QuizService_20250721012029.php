<?php

namespace App\Services;

use App\Interfaces\QuizRepositoryInterface;

class QuizService
{
    private QuizRepositoryInterface $quizRepository;

    public function __construct(QuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getCourseQuizzes($courseId)
    {
        return $this->quizRepository->getQuizByCourse($courseId);
    }

    public function getQuizDetails($quizId)
    {
        return $this->quizRepository->getQuizById($quizId);
    }

    public function createNewQuiz(array $quizDetails)
    {
        return $this->quizRepository->createQuiz($quizDetails);
    }

    public function updateQuizDetails($quizId, array $newDetails)
    {
        return $this->quizRepository->updateQuiz($quizId, $newDetails);
    }

    public function deleteQuiz($quizId)
    {
        return $this->quizRepository->deleteQuiz($quizId);
    }

    public function addQuestionToQuiz($quizId, array $questionDetails)
    {
        return $this->quizRepository->createQuestion($quizId, $questionDetails);
    }

    public function submitQuiz($quizId, $userId, array $answers)
    {
        return $this->quizRepository->submitQuizAttempt($quizId, $userId, $answers);
    }

    public function getQuizResult($quizId, $userId)
    {
        return $this->quizRepository->getQuizResults($quizId, $userId);
    }

    public function checkQuizAccess($quizId)
    {
        return $this->quizRepository->checkQuizAvailability($quizId);
    }

    public function getQuizAttempt($attemptId)
{
    return $this->quizRepository->getQuizAttemptById($attemptId);
}
}
