<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quiz Header -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-xl font-medium text-gray-900">{{ $quiz->title }}</h1>
                @if($quiz->time_limit)
                    <div id="quizTimer" class="hidden">
                        <div class="flex items-center text-sm font-medium text-gray-700">
                            <svg class="h-5 w-5 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kalan Süre: </span>
                            <span id="timeDisplay" class="ml-1 font-bold">00:00</span>
                        </div>
                    </div>
                @endif
            </div>

            @if($quiz->description)
                <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                    <p class="text-sm text-blue-800">{{ $quiz->description }}</p>
                </div>
            @endif
        </div>

        <!-- Quiz Form -->
        <form id="quizForm" method="POST" action="{{ route('courses.quizzes.submit', [$courseId, $quiz->id]) }}" class="bg-white shadow rounded-lg overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                @foreach($quiz->questions as $index => $question)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h2 class="text-lg font-medium text-gray-900">{{ $index + 1 }}. {{ $question->question_text }}</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $question->points }} puan
                            </span>
                        </div>

                        <div class="space-y-3">
                            @if($question->question_type === 'multiple_choice')
                                @foreach($question->answers as $answer)
                                    <div class="flex items-center">
                                        <input id="answer_{{ $answer->id }}" name="answers[{{ $question->id }}]" type="radio"
                                               value="{{ $answer->id }}" required
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <label for="answer_{{ $answer->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                                            {{ $answer->answer_text }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif($question->question_type === 'true_false')
                                <div class="flex items-center">
                                    <input id="answer_{{ $question->id }}_true" name="answers[{{ $question->id }}]" type="radio"
                                           value="{{ optional($question->answers->firstWhere('answer_text', 'Doğru'))->id }}" required
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="answer_{{ $question->id }}_true" class="ml-3 block text-sm font-medium text-gray-700">
                                        Doğru
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="answer_{{ $question->id }}_false" name="answers[{{ $question->id }}]" type="radio"
                                           value="{{ optional($question->answers->firstWhere('answer_text', 'Yanlış'))->id }}"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="answer_{{ $question->id }}_false" class="ml-3 block text-sm font-medium text-gray-700">
                                        Yanlış
                                    </label>
                                </div>
                            @else
                                <div>
                                    <label for="answer_{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-1">Cevabınız:</label>
                                    <input type="text" id="answer_{{ $question->id }}" name="answers[{{ $question->id }}]" required
                                           class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Sınavı Tamamla
                </button>
            </div>
        </form>
    </div>

    @if($quiz->time_limit)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quizForm = document.getElementById('quizForm');
            const timerDisplay = document.getElementById('quizTimer');
            const timeDisplay = document.getElementById('timeDisplay');

            timerDisplay.classList.remove('hidden');

            let timeLeft = {{ $quiz->time_limit * 60 }}; // Convert minutes to seconds

            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;

                timeDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert('Süreniz doldu! Quiz otomatik olarak gönderilecek.');
                    quizForm.submit();
                } else {
                    timeLeft--;
                }
            }

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        });
    </script>
    @endif
</x-layouts.app>
