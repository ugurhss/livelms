    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quiz Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                <p class="text-lg text-gray-500 mt-2">{{ $quiz->description }}</p>
            </div>
            <div class="flex gap-3">
                @can('edit-quiz', $quiz)
                <a href="{{ route('courses.quizzes.edit', [$courseId, $quiz->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Düzenle
                </a>
                @endcan

                @if($quiz->is_published)
                    <a href="{{ route('courses.quizzes.start', [$courseId, $quiz->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Quiz'e Başla
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="flex flex-col md:flex-row">
                <!-- Quiz Info Section -->
                <div class="w-full md:w-1/3 p-6 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center mb-4">
                        <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Quiz Detayları
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Durum:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quiz->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $quiz->is_published ? 'Yayında' : 'Taslak' }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Başlangıç:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $quiz->start_date ? $quiz->start_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Bitiş:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $quiz->end_date ? $quiz->end_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Süre Limiti:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $quiz->time_limit ? $quiz->time_limit.' dakika' : 'Yok' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Geçme Notu:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $quiz->passing_score }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Questions Section -->
                <div class="w-full md:w-2/3 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Sorular
                        </h3>

                        @can('edit-quiz', $quiz)
                        <a href="{{ route('courses.quizzes.questions.create', [$courseId, $quiz->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Yeni Soru Ekle
                        </a>
                        @endcan
                    </div>

                    @if($quiz->questions->isEmpty())
                        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz soru eklenmemiş</h3>
                            <p class="mt-1 text-sm text-gray-500">Öğrencilerin cevaplayabilmesi için sorular ekleyin.</p>
                            @can('edit-quiz', $quiz)
                            <div class="mt-6">
                                <a href="{{ route('courses.quizzes.questions.create', [$courseId, $quiz->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    İlk Soruyu Ekle
                                </a>
                            </div>
                            @endcan
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($quiz->questions as $question)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <button type="button" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-expanded="false" x-data="{ open: false }" @click="open = !open">
                                    <div class="flex items-center w-full">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-3">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="flex-grow-1 text-truncate mr-2">
                                            {{ $question->question_text }}
                                        </div>
                                        <small class="text-sm text-gray-500 ml-auto">
                                            {{ $question->points }} puan
                                        </small>
                                    </div>
                                    <svg class="ml-2 h-5 w-5 text-gray-400 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" class="p-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            @can('edit-quiz', $quiz)
                                            <a href="#" class="inline-flex items-center p-1.5 border border-gray-300 rounded-full shadow-sm text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <a href="#" class="inline-flex items-center p-1.5 border border-gray-300 rounded-full shadow-sm text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <a href="{{ route('courses.quizzes.index', $courseId) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Quiz Listesine Dön
        </a>
    </div>
