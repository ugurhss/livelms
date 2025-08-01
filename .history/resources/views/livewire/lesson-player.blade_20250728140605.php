<div>

<div class="text-center">
  <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-lesson-player-modal" data-hs-overlay="#hs-lesson-player-modal">
    Dersi Aç
  </button>
</div>

<!-- Fullscreen Video Player with Curriculum -->
<div id="hs-lesson-player-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto bg-gray-900" role="dialog" tabindex="-1" aria-labelledby="hs-lesson-player-modal-label">
  <div class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 ease-out transition-all size-full flex">
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
      <!-- Close Button -->
      <div class="absolute top-4 end-4 z-10">
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-800 text-gray-200 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#hs-lesson-player-modal">
          <span class="sr-only">Close</span>
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"/>
            <path d="m6 6 12 12"/>
          </svg>
        </button>
      </div>

      <!-- Video Player -->
      <div class="flex-1 flex items-center justify-center bg-black">
        <div class="w-full max-w-4xl aspect-video">
          <!-- Video Player Placeholder -->
          <div class="size-full flex items-center justify-center bg-gray-800 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="opacity-50">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Lesson Info -->
      <div class="bg-gray-800 p-4 border-t border-gray-700">
        <h2 id="hs-lesson-player-modal-label" class="text-xl font-bold text-white">Ders Başlığı</h2>
        <p class="text-gray-400 mt-1">Ders açıklaması buraya gelecek...</p>
      </div>
    </div>

    <!-- Curriculum Sidebar -->
    <div class="w-80 bg-gray-800 border-l border-gray-700 overflow-y-auto hidden md:block">
      <div class="p-4 sticky top-0 bg-gray-800 border-b border-gray-700 z-10">
        <h3 class="font-bold text-white">Müfredat</h3>
      </div>

      <div class="divide-y divide-gray-700">
        <!-- Course Module -->
        <div class="p-4">
          <h4 class="font-medium text-white mb-2">1. Modül Başlığı</h4>
          <ul class="space-y-2">
            <!-- Lesson Item -->
            <li>
              <a href="#" class="flex items-center gap-3 p-2 rounded-lg bg-gray-700 text-white">
                <span class="shrink-0 size-6 flex items-center justify-center bg-blue-600 rounded-full text-xs">1</span>
                <span>Ders Başlığı</span>
              </a>
            </li>
            <!-- Lesson Item -->
            <li>
              <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white">
                <span class="shrink-0 size-6 flex items-center justify-center border border-gray-600 rounded-full text-xs">2</span>
                <span>Ders Başlığı</span>
              </a>
            </li>
            <!-- More lessons... -->
          </ul>
        </div>

        <!-- Next Module -->
        <div class="p-4">
          <h4 class="font-medium text-white mb-2">2. Modül Başlığı</h4>
          <ul class="space-y-2">
            <!-- Lesson Item -->
            <li>
              <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white">
                <span class="shrink-0 size-6 flex items-center justify-center border border-gray-600 rounded-full text-xs">3</span>
                <span>Ders Başlığı</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.querySelectorAll('.hs-overlay').forEach((el) => HSOverlay.open(el));
    });
  });
</script>
