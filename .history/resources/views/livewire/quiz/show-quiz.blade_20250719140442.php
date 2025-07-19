<x-layouts.app :title="__('Quiz Details')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Quiz Detayları</h1>

        <!-- Quiz Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $quiz->description }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $quiz->passing_score }}% Passing Score
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ $quiz->time_limit ?? 'Unlimited' }} minutes
                        </span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ $quiz->start_date ? $quiz->start_date->format('M d, Y') : 'Available now' }}
                        </span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ $quiz->end_date ? $quiz->end_date->format('M d, Y') : 'No deadline' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
                {{-- <a href="{{ route('quizzes.attempt', ['courseId' => $courseId, 'quizId' => $quiz->id]) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    Start Quiz
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a> --}}
            </div>
        </div>

        <!-- Questions Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Questions</h3>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($quiz->questions as $question)
                <div class="p-6">
                    <div class="flex justify-between">
                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $loop->iteration }}. {{ $question->text }}</h4>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $question->points }} points</span>
                    </div>

                    @if($question->options->count() > 0)
                    <div class="mt-4 space-y-2">
                        @foreach($question->options as $option)
                        <div class="flex items-center">
                            <input type="{{ $question->type === 'multiple' ? 'checkbox' : 'radio' }}"
                                   class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                   {{ $option->is_correct ? 'checked' : '' }} disabled>
                            <label class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $option->text }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    No questions available for this quiz yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
