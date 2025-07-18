<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow overflow-hidden']) }}>
    @if(isset($title))
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
