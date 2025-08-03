<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('Dashboard') }}</h1>

        @if (Auth::user()->isAdmin())
            @livewire('dashboard.admin-dashboard')
        @elseif (Auth::user()->isInstructor())
            @livewire('dashboard.instructor-dashboard')
        @else
            @livewire('dashboard.student-dashboard')
        @endif
    </div>
</x-layouts.app>
