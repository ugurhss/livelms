<x-layouts.app>
    <!-- Loading state -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if($loading)
        <div class="flex justify-center items-center min-h-[50vh]">
            <div class="text-center">
                <div class="inline-flex items-center justify-center mb-4">
                    <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Kurs Yükleniyor</h3>
                <p class="mt-2 text-gray-600">Kurs içeriği yüklenirken lütfen bekleyin...</p>
            </div>
        </div>
    @endif

    <!-- Error state -->
    @if($error)
        <div class="bg-white rounded-xl shadow-sm p-8 text-center max-w-2xl mx-auto">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="mt-3 text-lg font-medium text-gray-900">Kurs Yüklenirken Hata Oluştu</h3>
            <p class="mt-2 text-gray-600">{{ $error }}</p>
            <div class="mt-6">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    Kurslara Geri Dön
                </a>
            </div>
        </div>
    @endif

    <!-- Course content -->
    @if($course)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Course header -->
            <div class="relative rounded-2xl overflow-hidden h-80 bg-gradient-to-r from-indigo-600 to-purple-600 mb-8">
                @if($course->thumbnail)
                    <img
                        src="{{ $course->thumbnail }}"
                        alt="{{ $course->title }}"
                        class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-20"
                    />
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                <div class="absolute bottom-0 left-0 p-8 text-white">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-white/20 backdrop-blur-sm">
                            {{ $course->category ? ucfirst(str_replace('-', ' ', $course->category)) : 'Genel' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            {{ $course->level === 'beginner' ? 'bg-green-500/20' :
                               ($course->level === 'intermediate' ? 'bg-yellow-500/20' : 'bg-red-500/20') }}">
                            {{ $course->level === 'beginner' ? 'Başlangıç' :
                              ($course->level === 'intermediate' ? 'Orta Seviye' : 'İleri Seviye') }}
                        </span>
                    </div>

                    <h1 class="text-4xl font-bold tracking-tight">{{ $course->title }}</h1>

                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($course->reviews_avg_rating ?? 0))
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-1">{{ number_format($course->reviews_avg_rating ?? 0, 1) }} ({{ $course->reviews_count ?? 0 }} değerlendirme)</span>
                        </div>
                        <span>•</span>
                        <span>{{ $course->students_count ?? 0 }} kayıtlı öğrenci</span>
                        <span>•</span>
                        <span>{{ $course->lessons_count ?? 0 }} ders</span>
                        <span>•</span>
                        <span>{{ $course->duration ?: '0' }} dakika</span>
                    </div>
                </div>

                @if($isInstructor)
                    <div class="absolute top-6 right-6 flex space-x-3">
                        <a
                            href="{{ route('courses.edit', $course->id) }}"
                            class="p-2.5 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors"
                            title="Kursu Düzenle"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- About section -->
                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Bu Kurs Hakkında</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($course->description)) !!}
                        </div>

                        <div class="mt-8 space-y-6">
                            @if($course->outcomes && count($course->outcomes) > 0)
                                <div>
                                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Kazanımlar</h3>
                                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($course->outcomes as $item)
                                            <li class="flex items-start">
                                                <svg class="w-5 h-5 text-green-500 mr-2.5 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-gray-700">{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($course->prerequisites && count($course->prerequisites) > 0)
                                <div>
                                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Ön Koşullar</h3>
                                    <ul class="space-y-2">
                                        @foreach($course->prerequisites as $item)
                                            <li class="flex items-start">
                                                <svg class="w-5 h-5 text-indigo-500 mr-2.5 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-gray-700">{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Curriculum section -->
                  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-8 border-b">
        <h2 class="text-2xl font-bold text-gray-900">Kurs Müfredatı</h2>
        @if($course->lessons && $course->lessons->count() > 0)
            <div class="mt-2 flex items-center justify-between">
                <span class="text-gray-600">{{ $course->lessons->count() }} ders • Toplam {{ $course->lessons->sum('duration_minutes') }} dakika</span>
                @if($expandableLessons)
                    <button
                        id="toggleAllAccordions"
                        class="text-indigo-600 text-sm font-medium hover:underline hover:text-indigo-700 transition-colors"
                    >
                        Tümünü Aç
                    </button>
                @endif
            </div>
        @endif
    </div>

    @if($course->lessons && $course->lessons->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($course->lessons as $index => $lesson)
                <div class="accordion-item overflow-hidden" x-data="{ open: false }">
                    <div
                        @click="open = !open"
                        class="accordion-header flex items-center justify-between p-6 cursor-pointer hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-center">
                            @if($expandableLessons)
                                <svg
                                    class="accordion-icon w-5 h-5 mr-3 text-gray-500 transition-transform duration-200"
                                    :class="open ? 'transform rotate-90 text-indigo-500' : ''"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            @endif
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $index + 1 }}. {{ $lesson->title }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $lesson->duration_minutes ?: '15' }} dakika</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if($lesson->is_free)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full mr-2">Ücretsiz</span>
                            @endif
                            @if($isEnrolled || $lesson->is_free)
                                <button
                                    wire:click="$emit('openLessonPlayer', {{ $lesson->id }})"
                                    class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm"
                                >
                                    İzle
                                </button>
                            @else
                                <button class="px-3 py-1 bg-gray-300 text-gray-600 rounded-md cursor-not-allowed text-sm" disabled>
                                    İzle
                                </button>
                            @endif
                        </div>
                    </div>

                    <div
                        class="accordion-content bg-gray-50 px-6"
                        :class="open ? 'active' : ''"
                        x-show="open"
                        x-collapse
                    >
                        <div class="pl-8 border-l-2 border-indigo-200 pb-6">
                            <p class="text-gray-700">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>

                            <div class="mt-4">
                                @if($isEnrolled || $lesson->is_free)
                                    <button
                                        wire:click="$emit('openLessonPlayer', {{ $lesson->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 underline"
                                    >
                                        Dersi Aç
                                    </button>
                                @else
                                    <div class="text-sm text-gray-600 bg-white p-3 rounded-md border border-gray-200">
                                        Bu dersi görmek için kursa kayıt olmalısınız
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-8 text-center text-gray-500">
            Henüz ders eklenmemiş.
        </div>
    @endif
</div>
@livewire('lesson-player', ['course' => $course])


                    <!-- Instructor section -->
                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Eğitmen Hakkında</h2>

                        <div class="flex flex-col sm:flex-row gap-6">
                            <img
                                src="{{ $course->instructor->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                alt="{{ $course->instructor->name }}"
                                class="w-24 h-24 rounded-full object-cover"
                            />
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $course->instructor->name }}</h3>
                                <p class="text-gray-600">{{ $course->instructor->title ?: 'Eğitmen' }}</p>
                                <div class="prose max-w-none text-gray-700 mt-4">
                                    {!! nl2br(e($course->instructor->bio ?: 'Eğitmen hakkında bilgi bulunmamaktadır.')) !!}
                                </div>

                                <div class="mt-4 flex justify-between max-w-xs">
                                    <div class="text-center">
                                        <div class="h5 mb-0 font-semibold">{{ $course->instructor->courses_count ?? 0 }}</div>
                                        <small class="text-muted">Kurs</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="h5 mb-0 font-semibold">{{ $course->instructor->students_count ?? 0 }}</div>
                                        <small class="text-muted">Öğrenci</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="h5 mb-0 font-semibold">{{ number_format($course->instructor->average_rating ?? 0, 1) }}</div>
                                        <small class="text-muted">Puan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews section -->
                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Değerlendirmeler</h2>

                        @if($course->reviews && $course->reviews->count() > 0)
                            <div class="space-y-6">
                                @foreach($course->reviews as $review)
                                    <div class="border-b pb-6 last:border-b-0 last:pb-0">
                                        <div class="flex items-center mb-3">
                                            <img src="{{ $review->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                 alt="{{ $review->user->name }}"
                                                 class="w-10 h-10 rounded-full mr-3">
                                            <div>
                                                <h4 class="font-medium">{{ $review->user->name }}</h4>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500 ml-auto">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-8">
                                Henüz değerlendirme yapılmamış.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                        <div class="text-center mb-6">
                            <span class="text-4xl font-bold text-gray-900">{{ $course->price ? '₺' . number_format($course->price, 2) : 'Ücretsiz' }}</span>
                            @if($course->price && $course->original_price)
                                <p class="text-gray-500 line-through mt-1">
                                    ₺{{ number_format($course->original_price, 2) }}
                                </p>
                            @endif
                        </div>

                        @if(!$isEnrolled && !$isInstructor)
                            @if(Auth::check())
                                <form action="{{ route('courses.enroll', $course) }}" method="POST" id="enrollForm">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                    >
                                        Kursa Kayıt Ol
                                    </button>
                                </form>
                            @else
                                <a
                                    href="{{ route('login', ['redirect' => url()->current()]) }}"
                                    class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                >
                                    Giriş Yap ve Kayıt Ol
                                </a>
                            @endif
                        @elseif($isEnrolled)
                            <div class="mb-4">
                                <div class="alert alert-success bg-green-100 text-green-800 p-3 rounded-md">
                                    Bu kursa kayıtlısınız.
                                </div>
                                {{-- <a href="{{ route('courses.learn', $course) }}" class="w-full block text-center mb-2 px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"> --}}
                                    Öğrenmeye Devam Et
                                </a>
                                <form action="{{ route('courses.unenroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        Kurstan Ayrıl
                                    </button>
                                </form>
                            </div>
                        @else
                            <a
                                href="{{ route('courses.edit', $course) }}"
                                class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                            >
                                Kursu Düzenle
                            </a>
                        @endif

                        <div class="mt-8 space-y-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-700">Toplam süre: {{ $course->duration ?: '0' }} dakika</span>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="text-gray-700">Süresiz erişim</span>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-5H7m2 5h6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                                <span class="text-gray-700">Tamamlama sertifikası</span>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span class="text-gray-700">30 gün para iade garantisi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Course stats -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                        <h3 class="text-xl font-bold mb-4 text-gray-900">Kurs İstatistikleri</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center">
                                <span class="text-gray-700">Toplam Ders</span>
                                <span class="font-medium">{{ $course->lessons->count() }}</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-700">Toplam Süre</span>
                                <span class="font-medium">{{ $course->lessons->sum('duration_minutes') }} dakika</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-700">Öğrenci Sayısı</span>
                                <span class="font-medium">{{ $course->students->count() }}</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-700">Tamamlama Oranı</span>
                                <span class="font-medium">{{ $course->enrollments->avg('progress') ?? 0 }}%</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Alpine.js and custom scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Accordion functionality
            const toggleAllBtn = document.getElementById('toggleAllAccordions');
            if (toggleAllBtn) {
                toggleAllBtn.addEventListener('click', function() {
                    const allExpanded = this.classList.contains('all-expanded');
                    const accordionContents = document.querySelectorAll('.accordion-content');
                    const accordionIcons = document.querySelectorAll('.accordion-icon');

                    if (allExpanded) {
                        // Close all
                        accordionContents.forEach(content => content.classList.remove('active'));
                        accordionIcons.forEach(icon => {
                            icon.classList.remove('rotate-90', 'text-indigo-500');
                        });
                        this.textContent = 'Tümünü Aç';
                    } else {
                        // Open all
                        accordionContents.forEach(content => content.classList.add('active'));
                        accordionIcons.forEach(icon => {
                            icon.classList.add('rotate-90', 'text-indigo-500');
                        });
                        this.textContent = 'Tümünü Kapat';
                    }

                    this.classList.toggle('all-expanded');
                });
            }

            // Handle lesson player events
            Livewire.on('loadLesson', (lessonId) => {
                const playerSection = document.querySelector('.lesson-player-container');
                if (playerSection) {
                    playerSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</x-layouts.app>
