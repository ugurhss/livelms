<?php

namespace App\Interfaces;

interface QuizRepositoryInterface
{
    public function getQuizByCourse($courseId);
    public function getQuizById($quizId);
    public function createQuiz(array $quizDetails);
    public function updateQuiz($quizId, array $newDetails);
    public function deleteQuiz($quizId);
    public function getQuestions($quizId);
    public function createQuestion($quizId, array $questionDetails);
    public function submitQuizAttempt($quizId, $userId, array $answers);
    public function getQuizResults($quizId, $userId);
    public function checkQuizAvailability($quizId);

}
