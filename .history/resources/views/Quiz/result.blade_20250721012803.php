<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Quiz Sonuçları</h1>

        @isset($courseId, $quizId, $attemptId)
            @livewire('result', [
                'attemptId' => $attemptId,
                'courseId' => $courseId,
                'quizId' => $quizId
            ])
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                Quiz bilgileri yüklenirken bir hata oluştu.
            </div>
        @endisset
    </div>
</x-layouts.app>
