<x-layouts.app>

<div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full h-full flex flex-col">
        <!-- Header -->
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-semibold">{{ $lesson->course->title }}</h3>
            <button onclick="window.history.back()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Main Content -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Lessons List (Left) -->
            <div class="w-1/4 border-r overflow-y-auto">
                <div class="p-4">
                    <h4 class="font-medium text-lg mb-4">Dersler</h4>
                    <ul class="space-y-2">
                        @foreach($courseLessons as $courseLesson)
                            <li>
                                <a href="{{ route('lesson.data', ['courseId' => $courseId, 'lessonId' => $courseLesson->id]) }}"
                                   class="block px-3 py-2 rounded {{ $lesson->id == $courseLesson->id ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }}">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $courseLesson->title }}</span>
                                        @if(auth()->user()->completedLessons->contains($courseLesson->id))
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $courseLesson->formatted_duration }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Video and Content (Right) -->
            <div class="flex-1 flex flex-col">
                <!-- Video Player -->
                <div class="bg-black flex-1 flex items-center justify-center">
                    @if($lesson->video_type === 'youtube')
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $lesson->video_id }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <video controls class="w-full h-full">
                            <source src="{{ $lesson->video_url }}" type="video/{{ $lesson->video_format }}">
                            Tarayıcınız video öğesini desteklemiyor.
                        </video>
                    @endif
                </div>

                <!-- Lesson Description and Actions -->
                <div class="border-t p-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $lesson->title }}</h2>
                    <p class="text-gray-700 mb-4">{{ $lesson->description }}</p>

                    <div class="flex justify-between items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress ? '100%' : '0%' }}"></div>
                        </div>

                        @if(!$progress)
                            <button onclick="markCompleted({{ $lesson->id }})" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Dersi Tamamla
                            </button>
                        @else
                            <span class="ml-4 px-4 py-2 bg-green-100 text-green-800 rounded">
                                Tamamlandı
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markCompleted(lessonId) {
    fetch(`/lessons/${lessonId}/mark-completed`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}
</script>

</x-layouts.app>
