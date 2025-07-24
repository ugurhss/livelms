<?php

namespace App\Interfaces;

interface CrudQuizRepositoryInterface
{
      public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getQuestions($quizId);
    public function addQuestion($quizId, array $questionData);
    public function updateQuestion($questionId, array $questionData);
    public function deleteQuestion($questionId);
    public function getQuestionWithAnswers($questionId);
}
