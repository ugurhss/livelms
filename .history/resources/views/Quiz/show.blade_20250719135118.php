  <x-layouts.app :title="__('quizzes')">

  @livewire('quiz.show-quiz', [
        'courseId' => $courseId,
        'quizId' => $quizId
    ])
</x-layouts.app>
