<?php

namespace App\Interfaces;

interface CrudQuizRepositoryInterface
{
   public function getAllQuizzes();
    public function getQuizById($quizId);
    public function createQuiz(array $quizDetails);
    public function updateQuiz($quizId, array $newDetails);
    public function deleteQuiz($quizId);
    public function getQuestions($quizId);
    public function createQuestion($quizId, array $questionDetails);
    public function updateQuestion($questionId, array $newDetails);
    public function deleteQuestion($questionId);
    public function getQuestionWithAnswers($questionId);
}
