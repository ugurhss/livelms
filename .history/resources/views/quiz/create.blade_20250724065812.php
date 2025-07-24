<x-layouts.app :title="__('quizzes')">


<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Yeni Quiz Oluştur</h1>

    <form action="{{ route('courses.quizzes.store', $courseId) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Quiz Başlığı</label>
            <input type="text" name="title" id="title" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Açıklama</label>
            <textarea name="description" id="description" rows="3"
                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="time_limit" class="block text-gray-700 font-medium mb-2">Süre Limiti (dakika)</label>
                <input type="number" name="time_limit" id="time_limit" min="1"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="passing_score" class="block text-gray-700 font-medium mb-2">Geçme Notu (%)</label>
                <input type="number" name="passing_score" id="passing_score" min="0" max="100" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="start_date" class="block text-gray-700 font-medium mb-2">Başlangıç Tarihi</label>
                <input type="datetime-local" name="start_date" id="start_date"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="end_date" class="block text-gray-700 font-medium mb-2">Bitiş Tarihi</label>
                <input type="datetime-local" name="end_date" id="end_date"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox h-5 w-5 text-blue-600">
                <span class="ml-2 text-gray-700">Yayınla</span>
            </label>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('courses.quizzes.index', $courseId) }}"
               class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg mr-2 hover:bg-gray-400 transition">
                İptal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kaydet
            </button>
        </div>
    </form>
</div>
</x-layouts.app>
