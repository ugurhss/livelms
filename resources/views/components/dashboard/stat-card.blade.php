@props(['title', 'value', 'icon', 'color' => 'gray'])

@php
    $colors = [
        'gray' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-300', 'iconBg' => 'bg-gray-100', 'iconColor' => 'text-gray-600'],
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-500', 'iconBg' => 'bg-blue-100', 'iconColor' => 'text-blue-600'],
        'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-500', 'iconBg' => 'bg-green-100', 'iconColor' => 'text-green-600'],
        'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-500', 'iconBg' => 'bg-yellow-100', 'iconColor' => 'text-yellow-600'],
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-500', 'iconBg' => 'bg-red-100', 'iconColor' => 'text-red-600'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-500', 'iconBg' => 'bg-purple-100', 'iconColor' => 'text-purple-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-500', 'iconBg' => 'bg-indigo-100', 'iconColor' => 'text-indigo-600'],
    ];

    $colorConfig = $colors[$color] ?? $colors['gray'];
@endphp

<div class="{{ $colorConfig['bg'] }} rounded-lg shadow p-4 border-l-4 {{ $colorConfig['border'] }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium {{ $colorConfig['text'] }}">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
        </div>
        <div class="{{ $colorConfig['iconBg'] }} p-3 rounded-full">
            @if($icon === 'users')
            <svg class="w-6 h-6 {{ $colorConfig['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            @elseif($icon === 'courses')
            <svg class="w-6 h-6 {{ $colorConfig['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            @elseif($icon === 'enrollments')
            <svg class="w-6 h-6 {{ $colorConfig['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @elseif($icon === 'revenue')
            <svg class="w-6 h-6 {{ $colorConfig['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @else
            <svg class="w-6 h-6 {{ $colorConfig['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            @endif
        </div>
    </div>
</div>
