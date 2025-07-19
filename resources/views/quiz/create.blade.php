<x-layouts.app :title="__('quizzes')">
    <div class="container mx-auto px-4 py-8">
        @livewire('quiz.create-quiz', ['courseId' => $courseId])
    </div>
</x-layouts.app>
