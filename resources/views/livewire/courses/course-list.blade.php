<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Courses</h1>
            <p class="text-lg text-gray-600 mt-2">
                {{ $user->role === 'instructor' ? 'Manage and create your educational content' : 'Discover your next learning journey' }}
            </p>
        </div>

        @if($user->role === 'instructor')
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
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select id="category" wire:model="filters.category" class="block w-full pl-10 sm:text-sm border border-gray-400 rounded-lg py-2 bg-white text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition-all duration-200">
                    <option value="">All Categories</option>
                    <option value="web-development">Web Development</option>
                    <option value="mobile-development">Mobile Development</option>
                    <option value="data-science">Data Science</option>
                    <option value="ui-ux-design">UI/UX Design</option>
                </select>
            </div>

            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                <select id="level" wire:model="filters.level" class="block w-full pl-10 sm:text-sm border border-gray-400 rounded-lg py-2 bg-white text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition-all duration-200">
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" wire:model="filters.status" class="block w-full pl-10 sm:text-sm border border-gray-400 rounded-lg py-2 bg-white text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition-all duration-200">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative rounded-md shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" wire:model.debounce.300ms="filters.search" placeholder="Search courses..."
                    class="block w-full pl-10 sm:text-sm border border-gray-400 rounded-lg py-2 bg-white text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition-all duration-200">
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if($courses->isEmpty() && !$hasFilters)
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900">No courses available</h3>
            <p class="mt-2 text-gray-600">
                {{ $user->role === 'instructor' ? 'Get started by creating your first course.' : 'Check back later for new courses.' }}
            </p>
            <div class="mt-6">
                @if($user->role === 'instructor')
                    <button onclick="window.location='{{ route('courses.create') }}'" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Create Course
                    </button>
                @elseif($hasFilters)
                    <button wire:click="clearFilters" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Clear Filters
                    </button>
                @endif
            </div>
        </div>
    @else
        <!-- Course Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-100">
                    <!-- Course Image -->
                    <div class="relative h-48 w-full">
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium leading-4
                                {{ $course->level === 'beginner' ? 'bg-green-100 text-green-800' :
                                   ($course->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($course->level) }}
                            </span>
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-indigo-600">{{ $course->category }}</span>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-sm text-gray-600">{{ number_format($course->reviews_avg_rating ?? 0, 1) }} ({{ $course->reviews_count ?? 0 }})</span>
                            </div>
                        </div>

                        <h3 class="mt-2 text-xl font-semibold text-gray-900 line-clamp-2">{{ $course->title }}</h3>
                        <p class="mt-3 text-gray-600 line-clamp-2">{{ $course->description }}</p>

                        <div class="mt-4 flex items-center">
                            <img src="{{ $course->instructor->avatar }}" alt="{{ $course->instructor->name }}" class="h-8 w-8 rounded-full">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $course->instructor->name }}</p>
                                <p class="text-sm text-gray-500">Instructor</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">
                                {{ $course->price ? '₹' . number_format($course->price, 2) : 'Free' }}
                            </span>
                            <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                {{ $this->isEnrolled($course->id) ? 'Continue' : 'View Details' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{-- {{ $courses->links('vendor.pagination.tailwind') }} --}}
        </div>
    @endif
</div>
