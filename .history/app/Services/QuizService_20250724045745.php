<?php

namespace App\Services;

use App\Interfaces\QuizRepositoryInterface;
use Carbon\Carbon;

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

    public function createNewQuiz(array $quizData)
    {
        return $this->quizRepository->createQuiz($quizData);
    }

    public function updateQuizDetails($quizId, array $newDetails)
    {
        return $this->quizRepository->updateQuiz($quizId, $newDetails);
    }

    public function deleteQuiz($quizId)
    {
        return $this->quizRepository->deleteQuiz($quizId);
    }

    public function addQuestionToQuiz($quizId, array $questionData)
    {
        return $this->quizRepository->createQuestion($quizId, $questionData);
    }

    public function checkQuizAccess($quizId, $userId)
    {
        $availability = $this->quizRepository->checkQuizAvailability($quizId);

        if (!$availability['available']) {
            return $availability;
        }

        // Kursa kayıtlı mı kontrolü eklenebilir
        // if (!$this->isUserEnrolled($userId, $quiz->course_id)) {
        //     return ['available' => false, 'message' => 'Bu quiz için kaydınız bulunmamaktadır.'];
        // }

        return $availability;
    }

    public function submitQuiz($quizId, $userId, array $answers)
    {
        return $this->quizRepository->submitQuizAttempt($quizId, $userId, $answers);
    }

    public function getQuizAttempt($attemptId)
    {
        return $this->quizRepository->getQuizAttemptById($attemptId);
    }

    public function hasUserCompletedQuiz($quizId, $userId)
    {
        $attempt = $this->quizRepository->getQuizResults($quizId, $userId);
        return $attempt && $attempt->completed_at !== null;
    }
}
