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
        return $this->quizRepository->getAllQuizzes();
    }

    public function getQuizById($quizId)
    {
        return $this->quizRepository->getQuizById($quizId);
    }

    public function createQuiz(array $quizDetails)
    {
        return $this->quizRepository->createQuiz($quizDetails);
    }

    public function updateQuiz($quizId, array $newDetails)
    {
        return $this->quizRepository->updateQuiz($quizId, $newDetails);
    }

    public function deleteQuiz($quizId)
    {
        return $this->quizRepository->deleteQuiz($quizId);
    }

    public function getQuestions($quizId)
    {
        return $this->quizRepository->getQuestions($quizId);
    }

    public function createQuestion($quizId, array $questionDetails)
    {
        $question = $this->quizRepository->createQuestion($quizId, $questionDetails);

        if ($questionDetails['question_type'] === 'multiple_choice' && isset($questionDetails['answers'])) {
            foreach ($questionDetails['answers'] as $answer) {
                $question->answers()->create([
                    'answer_text' => $answer['text'],
                    'is_correct' => $answer['is_correct']
                ]);
            }
        }

        return $question;
    }

    public function updateQuestion($questionId, array $newDetails)
    {
        $question = $this->quizRepository->updateQuestion($questionId, $newDetails);

        if (isset($newDetails['answers'])) {
            $questionModel = Question::find($questionId);
            $questionModel->answers()->delete();

            foreach ($newDetails['answers'] as $answer) {
                $questionModel->answers()->create([
                    'answer_text' => $answer['text'],
                    'is_correct' => $answer['is_correct']
                ]);
            }
        }

        return $question;
    }

    public function deleteQuestion($questionId)
    {
        return $this->quizRepository->deleteQuestion($questionId);
    }

    public function getQuestionWithAnswers($questionId)
    {
        return $this->quizRepository->getQuestionWithAnswers($questionId);
    }
}
