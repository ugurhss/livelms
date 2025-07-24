<x-layouts.app>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Courses</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $course->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            {{ $course->quizzes->count() }} {{ Str::plural('Quiz', $course->quizzes->count()) }}
                        </span>

                        <a href="{{ route('quizzes.create', $course->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                            Add Quiz
                        </a>
                    </div>

                    @if($course->quizzes->isNotEmpty())
                        <div class="mt-4 pt-4 border-t">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Quizzes:</h3>
                            <ul class="space-y-2">
                                @foreach($course->quizzes as $quiz)
                                    <li class="flex justify-between items-center">
                                        <span class="text-gray-700">{{ $quiz->title }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full
                                              {{ $quiz->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $quiz->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
</x-layouts.app>
