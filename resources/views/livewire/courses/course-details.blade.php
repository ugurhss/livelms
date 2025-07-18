
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Loading state -->
    @if($loading)
        <div class="flex justify-center items-center min-h-[50vh]">
            <div class="text-center">
                <div class="inline-flex items-center justify-center mb-4">
                    <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Loading Course Details</h3>
                <p class="mt-2 text-gray-600">Please wait while we load the course content...</p>
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
            <h3 class="mt-3 text-lg font-medium text-gray-900">Error Loading Course</h3>
            <p class="mt-2 text-gray-600">{{ $error }}</p>
            <div class="mt-6">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    Back to Courses
                </a>
            </div>
        </div>
    @endif

    <!-- Course content -->
    @if($course)
        <div class="space-y-8">
            <!-- Course header -->
            <div class="relative rounded-2xl overflow-hidden h-80 bg-gradient-to-r from-indigo-600 to-purple-600">
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
                            {{ $course->category ? ucfirst(str_replace('-', ' ', $course->category)) : 'General' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            {{ $course->level === 'beginner' ? 'bg-green-500/20' :
                               ($course->level === 'intermediate' ? 'bg-yellow-500/20' : 'bg-red-500/20') }}">
                            {{ ucfirst($course->level) }}
                        </span>
                    </div>

                    <h1 class="text-4xl font-bold tracking-tight">{{ $course->title }}</h1>

                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="ml-1">{{ number_format($course->reviews_avg_rating ?? 0, 1) }} ({{ $course->reviews_count ?? 0 }} reviews)</span>
                        </div>
                        <span>•</span>
                        <span>{{ $course->students_count ?? 0 }} students enrolled</span>
                        <span>•</span>
                        <span>{{ $course->lessons_count ?? 0 }} lessons</span>
                    </div>
                </div>

                @if($isInstructor)
                    <div class="absolute top-6 right-6 flex space-x-3">
                        <a
                            href="{{ route('courses.edit', $course->id) }}"
                            class="p-2.5 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors"
                            title="Edit Course"
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
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">About This Course</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($course->description)) !!}
                        </div>

                        <div class="mt-8 space-y-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-3 text-gray-900">What You'll Learn</h3>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($course->outcomes ?? [] as $item)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2.5 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="text-gray-700">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-xl font-semibold mb-3 text-gray-900">Prerequisites</h3>
                                <ul class="space-y-2">
                                    @foreach($course->prerequisites ?? [] as $item)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-indigo-500 mr-2.5 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-gray-700">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Curriculum section -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-8 border-b">
                            <h2 class="text-2xl font-bold text-gray-900">Course Curriculum</h2>
                            @if($course->lessons && $course->lessons->count() > 0)
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-gray-600">{{ $course->lessons->count() }} lessons</span>
                                    <button
                                        wire:click="toggleExpandAll"
                                        class="text-indigo-600 text-sm font-medium hover:underline hover:text-indigo-700 transition-colors"
                                    >
                                        {{ $expandAll ? 'Collapse All' : 'Expand All' }}
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if($course->lessons && $course->lessons->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($course->lessons as $index => $lesson)
                                    <div class="overflow-hidden">
                                        <div
                                            wire:click="toggleLesson({{ $index }})"
                                            class="flex items-center justify-between p-6 cursor-pointer hover:bg-gray-50 transition-colors"
                                        >
                                            <div class="flex items-center">
                                                <svg
                                                    class="w-5 h-5 mr-3 text-gray-500 transition-transform duration-200
                                                    {{ in_array($index, $expandedLessons) ? 'transform rotate-90 text-indigo-500' : '' }}"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $index + 1 }}. {{ $lesson->title }}</h4>
                                                    <p class="text-sm text-gray-500 mt-1">{{ $lesson->duration ?: '15 min' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-gray-400">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        @if(in_array($index, $expandedLessons))
                                            <div class="bg-gray-50 px-6 pb-6">
                                                <div class="pl-8 border-l-2 border-indigo-200">
                                                    <p class="text-gray-700">{{ $lesson->description ?: 'No description available.' }}</p>

                                                    <div class="mt-4">
                                                        @if($isEnrolled || $isInstructor)
                                                            <button
                                                                wire:click="startLesson({{ $index }})"
                                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                                            >
                                                                Start Lesson
                                                            </button>
                                                        @else
                                                            <div class="text-sm text-gray-600 bg-white p-3 rounded-md border border-gray-200">
                                                                Enroll in this course to access this lesson
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-500">
                                No lessons available yet.
                            </div>
                        @endif
                    </div>

                    <!-- Instructor section -->
                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">About the Instructor</h2>

                        <div class="flex flex-col sm:flex-row gap-6">
                            <img
                                src="{{ $course->instructor->avatar }}"
                                alt="{{ $course->instructor->name }}"
                                class="w-24 h-24 rounded-full object-cover"
                            />
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $course->instructor->name }}</h3>
                                <p class="text-gray-600">{{ $course->instructor->title ?: 'Course Instructor' }}</p>
                                <div class="prose max-w-none text-gray-700 mt-4">
                                    {!! nl2br(e($course->instructor->bio ?: 'No instructor bio available.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                        <div class="text-center mb-6">
                            <span class="text-4xl font-bold text-gray-900">{{ $course->price ? '₹' . number_format($course->price, 2) : 'Free' }}</span>
                            @if($course->price && $course->original_price)
                                <p class="text-gray-500 line-through mt-1">
                                    ₹{{ number_format($course->original_price, 2) }}
                                </p>
                            @endif
                        </div>

                        @if(!$isEnrolled && !$isInstructor)
                            <button
                                wire:click="enrollInCourse"
                                wire:loading.attr="disabled"
                                class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                            >
                                <span wire:loading.remove>Enroll Now</span>
                                <span wire:loading>
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        @elseif($isEnrolled)
                            <a
                                {{-- href="{{ route('courses.learn', $course->id) }}" --}}
                                class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            >
                                Continue Learning
                            </a>
                        @else
                            <a
                                href="{{ route('courses.edit', $course->id) }}"
                                class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                            >
                                Edit Course Content
                            </a>
                        @endif

                        <div class="mt-8 space-y-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-700">Total course length: 5 hours</span>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="text-gray-700">Full lifetime access</span>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                                <span class="text-gray-700">Certificate of completion</span>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span class="text-gray-700">30-day money-back guarantee</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($selectedLesson !== null)
            @livewire('lesson-viewer', [
                'lesson' => $course->lessons[$selectedLesson],
                'hasPreviousLesson' => $selectedLesson > 0,
                'hasNextLesson' => $selectedLesson < count($course->lessons) - 1,
                'key' => 'lesson-'.$selectedLesson
            ], key('lesson-'.$selectedLesson))
        @endif
    @endif
</div>
