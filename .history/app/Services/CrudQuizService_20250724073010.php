<?php

namespace App\Services;

use App\Interfaces\CrudQuizRepositoryInterface;


class CrudQuizService
{
  protected $quizRepository;

    public function __construct(CrudQuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getAllQuizzes()
    {
        return $this->quizRepository->getAll();
    }

    public function getQuiz($id)
    {
        return $this->quizRepository->find($id);
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

    public function getQuizQuestions($quizId)
    {
        return $this->quizRepository->getQuestions($quizId);
    }

    public function addQuestionToQuiz($quizId, array $questionData)
    {
        return $this->quizRepository->addQuestion($quizId, $questionData);
    }

    public function updateQuestion($questionId, array $questionData)
    {
        return $this->quizRepository->updateQuestion($questionId, $questionData);
    }

    public function deleteQuestion($questionId)
    {
        return $this->quizRepository->deleteQuestion($questionId);
    }

    public function getQuestion($questionId)
    {
        return $this->quizRepository->getQuestionWithAnswers($questionId);
    }
}
