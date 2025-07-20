  <x-layouts.app :title="__('quizzes')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">soru oluştur</h1>

        <!-- Quiz Başlığı -->
@livewire('quiz.create-quiz', [
            'courseId' => $courseId,
            'quizId' => $quizId
        ])
    </div>


</x-layouts.app>
