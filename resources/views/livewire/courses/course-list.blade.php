<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Courses</h1>
            <p class="text-lg text-gray-600 mt-2">
                {{ $user && $user->role === 'instructor' ? 'Manage and create your educational content' : 'Discover your next learning journey' }}
            </p>
        </div>

        @if($user && $user->role === 'instructor')
            <button onclick="window.location='{{ route('courses.create') }}'"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create Course
            </button>
        @endif
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select wire:model.lazy="filters.category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Level Filter -->
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                <select wire:model.lazy="filters.level" id="level" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Levels</option>
                    @foreach($levels ?? [] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            @if($user && $user->role === 'instructor')
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.lazy="filters.status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            @endif

            <!-- Search Filter -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <input wire:model.debounce.500ms="filters.search" type="text" id="search" placeholder="Search courses..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @if($filters['search'] ?? false)
                        <button wire:click="$set('filters.search', '')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if($hasFilters)
            <div class="mt-4 flex justify-end">
                <button wire:click="clearFilters" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Clear all filters
                </button>
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
                @if($hasFilters)
                    No courses match your filters
                @else
                    No courses available
                @endif
            </h3>
            <p class="mt-1 text-gray-500">
                @if($hasFilters)
                    Try adjusting your search or filter to find what you're looking for.
                @else
                    Check back later or contact us if you have any questions.
                @endif
            </p>
            @if($hasFilters)
                <div class="mt-6">
                    <button wire:click="clearFilters" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear all filters
                    </button>
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
                        @if($course->level ?? false)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium leading-4
                                    {{ $course->level === 'beginner' ? 'bg-green-100 text-green-800' :
                                       ($course->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Course Content -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-indigo-600">
                                {{ $course->category->name ?? 'Uncategorized' }}
                            </span>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-sm text-gray-600">
                                    {{ number_format($course->reviews_avg_rating ?? 0, 1) }} ({{ $course->reviews_count ?? 0 }})
                                </span>
                            </div>
                        </div>

                        <h3 class="mt-2 text-xl font-semibold text-gray-900 line-clamp-2">{{ $course->title }}</h3>
                        <p class="mt-3 text-gray-600 line-clamp-2">{{ $course->description ?? 'No description available' }}</p>

                        <div class="mt-4 flex items-center">
                            <img src="{{ $course->instructor->avatar ?? asset('images/default-avatar.jpg') }}"
                                 alt="{{ $course->instructor->name ?? 'Instructor' }}"
                                 class="h-8 w-8 rounded-full">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $course->instructor->name ?? 'Unknown Instructor' }}</p>
                                <p class="text-sm text-gray-500">Instructor</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">
                                @if($course->price && $course->price > 0)
                                    ₹{{ number_format($course->price, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                         <a href="{{ route('courses.public.show', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                {{ $user && $this->isEnrolled($course->id) ? 'Continue' : 'View Details' }}
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
