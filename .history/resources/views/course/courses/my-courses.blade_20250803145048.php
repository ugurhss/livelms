<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Kayıtlı Kurslarım</h1>

            @if($courses->isNotEmpty())
                <div class="text-sm text-gray-500">
                    Toplam {{ $courses->total() }} kurs
                </div>
            @endif
        </div>

        @if($courses->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Henüz hiç kursa kayıtlı değilsiniz</h3>
                <p class="mt-1 text-sm text-gray-500">Hemen yeni bir kurs keşfetmeye başlayın ve becerilerinizi geliştirin.</p>
                <div class="mt-6">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kursları Keşfet
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="relative">
                            <img src="{{ $course->thumbnail ?? asset('images/default-course.jpg') }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            <div class="absolute top-2 right-2 bg-indigo-600 text-white text-xs font-semibold px-2 py-1 rounded">
                                {{ $course->level === 'beginner' ? 'Başlangıç' : ($course->level === 'intermediate' ? 'Orta Seviye' : 'İleri Seviye') }}
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ Str::limit($course->description, 80) }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-500 mb-1">
                                    <span>İlerleme</span>
<span>{{ $course->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                 <div style="width: {{ $course->progress ?? 0 }}%"></div>

                            </div>
                            <div class="mb-4">
    <div class="flex justify-between text-sm text-gray-500 mb-1">
        <span>İlerleme</span>
        <span>
            @php
                $enrollment = $course->enrollments->firstWhere('user_id', auth()->id());
                $progress = $enrollment->progress ?? 0;
                echo $progress . '%';
            @endphp
        </span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-indigo-600 h-2.5 rounded-full"
             style="width: {{ $progress }}%"></div>
    </div>
</div>

                            <div class="flex justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $course->lessons_count }} ders
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $course->students_count }} öğrenci
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('courses.show', $course->id) }}" class="flex-1 text-center px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                                    Devam Et
                                </a>
                                {{-- <a href="{{ route('courses.quiz', $course->id) }}" class="px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    Sınav
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
