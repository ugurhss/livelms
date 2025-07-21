<x-layouts.app :title="__('quizzes')">
    <div class="container mx-auto px-4 py-8">
        @livewire('courses.edit-course', ['id' => $course->id])

    </div>
</x-layouts.app>
