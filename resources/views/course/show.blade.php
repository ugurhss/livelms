<x-layouts.app :title="__('quizzes')">
    <div class="container mx-auto px-4 py-8">
   @livewire('courses.course-details', [
        'courseId' => $course->id, // Livewire'a ID'yi aktar
        'initialIsEnrolled' => $isEnrolled, // İsteğe bağlı: Controller'dan veri geçebilirsiniz
        'initialIsInstructor' => $isInstructor,
    ])
    </div>
</x-layouts.app>
