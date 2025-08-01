<div>

<div class="text-center">
  <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="fullscreen-video-modal" data-hs-overlay="#fullscreen-video-modal">
    Video Modalı Aç
  </button>
</div>

<div id="fullscreen-video-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="fullscreen-video-modal-label">
  <div class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all w-full h-full m-0 sm:mx-auto">
    <div class="max-h-full overflow-hidden flex flex-col bg-white border border-gray-200 shadow-2xl rounded-none pointer-events-auto h-full">
      <!-- Header -->
      <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
        <h3 id="fullscreen-video-modal-label" class="font-bold text-gray-800">
          Eğitim Videosu
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#fullscreen-video-modal">
          <span class="sr-only">Kapat</span>
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>

      <!-- Main Content -->
      <div class="flex flex-col md:flex-row flex-1 overflow-hidden">
        <!-- Video Section -->
        <div class="w-full md:w-2/3 bg-black flex flex-col">
          <div class="flex-1 flex items-center justify-center">
            <div class="w-full aspect-video bg-gray-800 flex items-center justify-center text-white">
              <!-- Video Player Placeholder -->
              <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>

          <!-- Video Description -->
          <div class="p-4 border-t border-gray-200 bg-white">
            <h4 class="font-semibold text-gray-800 mb-2">Ders Açıklaması</h4>
            <p class="text-sm text-gray-600">
              Bu derste temel HTML ve CSS yapılarını öğreneceksiniz. Responsive tasarım teknikleri ve modern CSS özellikleri üzerinde duracağız.
            </p>
          </div>
        </div>

        <!-- Curriculum Section -->
        <div class="w-full md:w-1/3 border-l border-gray-200 overflow-y-auto bg-gray-50">
          <div class="p-4">
            <h4 class="font-semibold text-gray-800 mb-3">Müfredat</h4>

            <div class="space-y-2">
              <!-- Module -->
              <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button class="w-full flex justify-between items-center p-3 bg-gray-100 text-left">
                  <span class="font-medium text-gray-800">1. Modül: HTML Temelleri</span>
                  <svg class="shrink-0 size-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>
                <div class="p-2 space-y-1">
                  <a href="#" class="block p-2 text-sm text-blue-600 hover:bg-blue-50 rounded">1.1 HTML'e Giriş</a>
                  <a href="#" class="block p-2 text-sm text-blue-600 hover:bg-blue-50 rounded">1.2 Temel Etiketler</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<!-- HSOverlay scriptini yükle -->
<script src="https://cdn.jsdelivr.net/npm/hs-overlay@latest/dist/hs-overlay.min.js"></script>
<script>
  // HSOverlay'ı başlat
  document.addEventListener('DOMContentLoaded', function() {
    new HSOverlay('#fullscreen-video-modal').init();
  });
</script>
