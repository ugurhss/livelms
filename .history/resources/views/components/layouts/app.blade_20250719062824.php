<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen">
    <aside class="w-64 bg-gray-200 p-4">
        <h2 class="font-bold mb-4">Sidebar</h2>
        <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="https://github.com">Repository</a></li>
        </ul>
    </aside>
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>
</body>
</html>
