    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Quiz Sonucu: {{ $result->quiz->title }}</h1>
                <p class="mt-2 text-gray-600">Quiz performansınızı detaylı olarak görüntüleyebilirsiniz</p>
            </div>
            <a href="{{ route('courses.quizzes.index', $courseId) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quiz Listesine Dön
            </a>
        </div>

        <!-- Result Summary -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
            <div class="p-6 {{ $result->is_passed ? 'bg-green-50' : 'bg-red-50' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Puanınız</h2>
                        <div class="text-5xl font-bold {{ $result->is_passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ round($result->score) }}%
                        </div>
                    </div>
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Sonuç</h2>
                        @if($result->is_passed)
                            <div class="text-2xl font-semibold text-green-600">Tebrikler, geçtiniz!</div>
                            <p class="mt-1 text-gray-600">Geçme notu: {{ $result->quiz->passing_score }}%</p>
                        @else
                            <div class="text-2xl font-semibold text-red-600">Maalesef, geçemediniz</div>
                            <p class="mt-1 text-gray-600">Geçme notu: {{ $result->quiz->passing_score }}%</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Details -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Soru Detayları
                </h3>
            </div>

            <div class="p-6 space-y-4">
                @foreach($result->quiz->questions as $index => $question)
                    @php
                        $userAnswer = $result->userAnswers->firstWhere('question_id', $question->id);
                        $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                    @endphp

                    <div class="p-4 rounded-lg border {{ $isCorrect ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-medium text-gray-900">{{ $index + 1 }}. {{ $question->question_text }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isCorrect ? 'Doğru' : 'Yanlış' }} ({{ $question->points }} puan)
                            </span>
                        </div>

                        @if($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
                            <div class="mb-2 text-sm">
                                <span class="font-medium text-gray-700">Doğru Cevap:</span>
                                <span class="ml-1">{{ $question->answers->firstWhere('is_correct', true)->answer_text }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Sizin Cevabınız:</span>
                                <span class="ml-1">{{ $userAnswer && $userAnswer->answer ? $userAnswer->answer->answer_text : '-' }}</span>
                            </div>
                        @else
                            <div class="mb-2 text-sm">
                                <span class="font-medium text-gray-700">Doğru Cevap:</span>
                                <span class="ml-1">{{ $question->answers->first()->answer_text }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-medium text-gray-700">Sizin Cevabınız:</span>
                                <span class="ml-1">{{ $userAnswer ? $userAnswer->answer_text : '-' }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                <a href="{{ route('courses.quizzes.index', $courseId) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quiz Listesine Dön
                </a>
            </div>
        </div>
    </div>
