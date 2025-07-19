<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Quiz Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-8">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $quiz->title }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        {{ $quiz->description }}
                    </p>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/30 px-4 py-3 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium text-blue-600 dark:text-blue-400">
                            Time Limit: {{ $quiz->time_limit ? $quiz->time_limit.' minutes' : 'No limit' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Passing Score -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Passing Score</h3>
            <div class="flex items-center">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full" style="width: {{ $quiz->passing_score }}%"></div>
                </div>
                <span class="ml-4 text-gray-700 dark:text-gray-300 font-medium">{{ $quiz->passing_score }}%</span>
            </div>
        </div>

        <!-- Availability -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Availability</h3>
            <div class="space-y-2">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-2 text-gray-600 dark:text-gray-300">
                        Starts: {{ $quiz->start_date ? $quiz->start_date->format('M d, Y H:i') : 'Immediately' }}
                    </span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="ml-2 text-gray-600 dark:text-gray-300">
                        Ends: {{ $quiz->end_date ? $quiz->end_date->format('M d, Y H:i') : 'No deadline' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quiz Actions -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('quizzes.attempt', ['courseId' => $courseId, 'quizId' => $quiz->id]) }}"
                   class="w-full flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                    Start Quiz
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('quizzes.edit', ['courseId' => $courseId, 'quizId' => $quiz->id]) }}"
                   class="w-full flex justify-center items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium rounded-lg transition duration-150 ease-in-out">
                    Edit Quiz
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Questions Preview -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Questions Preview</h2>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($quiz->questions as $question)
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $loop->iteration }}. {{ $question->text }}</h3>
                        @if($question->image)
                        <div class="mt-3">
                            <img src="{{ asset('storage/'.$question->image) }}" alt="Question image" class="max-w-full h-auto rounded-lg">
                        </div>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $question->points }} points
                    </span>
                </div>

                <div class="mt-4 space-y-2">
                    @foreach($question->options as $option)
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="{{ $question->type === 'multiple' ? 'checkbox' : 'radio' }}"
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                   {{ $option->is_correct ? 'checked' : '' }} disabled>
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-gray-700 dark:text-gray-300">{{ $option->text }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No questions added yet.
            </div>
            @endforelse
        </div>
    </div>
</div>
