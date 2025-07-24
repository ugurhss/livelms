<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-900">Tüm Kurslar</h1>
                <p class="text-lg text-gray-600 mt-2">
                    @auth
                        {{ Auth::user()->role === 'instructor' ? 'Eğitim içeriklerinizi yönetin ve oluşturun' : 'Bir sonraki öğrenme yolculuğunuzu keşfedin' }}
                    @else
                        Bir sonraki öğrenme yolculuğunuzu keşfedin
                    @endauth
                </p>
            </div>

            @can('create', App\Models\Course::class)
                <a href="{{ route('courses.courses.create') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Yeni Kurs Oluştur
                </a>
            @endcan
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form action="{{ route('courses.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Search Filter -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Kurs Ara</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" placeholder="Kurs ara..."
                                   value="{{ request('search') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @if(request('search'))
                                <a href="{{ route('courses.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Level Filter -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Seviye</label>
                        <select name="level" id="level" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Tüm Seviyeler</option>
                            @foreach(['beginner' => 'Başlangıç', 'intermediate' => 'Orta', 'advanced' => 'İleri'] as $key => $value)
                                <option value="{{ $key }}" {{ request('level') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-[42px]">
                            Filtrele
                        </button>
                    </div>
                </div>
            </form>

            @if(request()->hasAny(['search', 'level']))
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        Tüm filtreleri temizle
                    </a>
                </div>
            @endif
        </div>

        <!-- Empty State -->
        @if($courses->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">
                    @if(request()->hasAny(['search', 'level']))
                        Filtrelerinizle eşleşen kurs bulunamadı
                    @else
                        Henüz kurs mevcut değil
                    @endif
                </h3>
                <p class="mt-1 text-gray-500">
                    @if(request()->hasAny(['search', 'level']))
                        Arama veya filtrelerinizi değiştirmeyi deneyin.
                    @else
                        Daha sonra tekrar kontrol edin veya sorularınız için bizimle iletişime geçin.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'level']))
                    <div class="mt-6">
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Tüm filtreleri temizle
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- Course Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-100">
                        <!-- Course Image -->
                        <div class="relative h-48 w-full">
                            <img src="{{ $course->thumbnail ?? asset('images/default-course.jpg') }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                            @if($course->level)
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium leading-4
                                        {{ $course->level === 'beginner' ? 'bg-green-100 text-green-800' :
                                           ($course->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        @if($course->level === 'beginner')
                                            Başlangıç
                                        @elseif($course->level === 'intermediate')
                                            Orta
                                        @else
                                            İleri
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Course Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 line-clamp-2">{{ $course->title }}</h3>
                            <p class="mt-3 text-gray-600 line-clamp-2">{{ $course->description ?? 'Açıklama yok' }}</p>

                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-gray-900">
                                        @if($course->price && $course->price > 0)
                                            {{ number_format($course->price, 2) }} ₺
                                        @else
                                            Ücretsiz
                                        @endif
                                    </span>
                                    @if($course->original_price > $course->price)
                                        <span class="ml-2 text-sm text-gray-500 line-through">{{ number_format($course->original_price, 2) }} ₺</span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">{{ $course->duration }} dakika</span>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('courses.show', $course->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Detayları Gör
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
