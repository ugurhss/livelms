
  <!-- Tailwind CSS CDN -->



  <div class="text-center">
    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#fullscreen-video-modal">
      Video Modalı Aç
    </button>
  </div>

  <!-- Modal -->
  <div id="fullscreen-video-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all w-full h-full m-0">
      <div class="max-h-full overflow-hidden flex flex-col bg-white border border-gray-200 shadow-2xl rounded-none h-full">
        <!-- Header -->
        <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
          <h3 class="font-bold text-gray-800">
            Eğitim Videosu
          </h3>
          <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200" data-hs-overlay="#fullscreen-video-modal">
            <span class="sr-only">Kapat</span>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"/>
              <path d="m6 6 12 12"/>
            </svg>
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
                Bu derste temel HTML ve CSS yapılarını öğreneceksiniz. Responsive tasarım teknikleri ve modern CSS özellikleri üzerinde duracağız. Ders sonunda basit bir portfolio sayfası oluşturmuş olacaksınız.
              </p>

              <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                <span>45 dakika</span>
                <span>•</span>
                <span>Seviye: Başlangıç</span>
              </div>
            </div>
          </div>

          <!-- Curriculum Section -->
          <div class="w-full md:w-1/3 border-l border-gray-200 overflow-y-auto bg-gray-50">
            <div class="p-4">
              <h4 class="font-semibold text-gray-800 mb-3">Müfredat</h4>

              <div class="space-y-2">
                <!-- Module 1 -->
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
                    <a href="#" class="block p-2 text-sm text-blue-600 hover:bg-blue-50 rounded">1.3 Formlar</a>
                  </div>
                </div>

                <!-- Module 2 -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                  <button class="w-full flex justify-between items-center p-3 bg-gray-100 text-left">
                    <span class="font-medium text-gray-800">2. Modül: CSS Temelleri</span>
                    <svg class="shrink-0 size-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </button>
                  <div class="hidden p-2 space-y-1">
                    <a href="#" class="block p-2 text-sm text-blue-600 hover:bg-blue-50 rounded">2.1 CSS'e Giriş</a>
                    <a href="#" class="block p-2 text-sm text-blue-600 hover:bg-blue-50 rounded">2.2 Flexbox</a>
                    <a href="#" class="block p-2 text-sm text-blue-600 hover:bg-blue-50 rounded">2.3 Grid</a>
                  </div>
                </div>

                <!-- Current Video -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
                  <div class="flex items-start gap-3">
                    <div class="bg-blue-100 text-blue-800 rounded-full p-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                      </svg>
                    </div>
                    <div>
                      <h5 class="font-medium text-blue-800">Şu an izleniyor</h5>
                      <p class="text-sm text-blue-600">1.1 HTML'e Giriş</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center gap-x-2 py-3 px-4 border-t border-gray-200">
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <span>Tamamlanma: %25</span>
            <div class="w-24 bg-gray-200 rounded-full h-2">
              <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
            </div>
          </div>
          <div class="flex gap-2">
            <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
              Önceki
            </button>
            <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
              Sonraki
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

    <script src="https://cdn.tailwindcss.com"></script>
  <!-- HSOverlay CSS -->
  <link rel="stylesheet" href="https://unpkg.com/@preline/overlay@1.0.0/dist/overlay.css">
  <!-- HSOverlay JS -->
  <script src="https://unpkg.com/@preline/overlay@1.0.0/dist/overlay.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // HSOverlay'ı başlat
      const overlayEls = document.querySelectorAll('.hs-overlay');
      overlayEls.forEach(function(overlayEl) {
        new HSOverlay(overlayEl).init();
      });

      // Müfredat accordion fonksiyonelliği
      document.querySelectorAll('[data-hs-overlay]').forEach(function(button) {
        button.addEventListener('click', function() {
          const target = this.getAttribute('data-hs-overlay');
          const overlay = document.querySelector(target);
          new HSOverlay(overlay).show();
        });
      });

      // Modül açma/kapama
      document.querySelectorAll('.border-gray-200 button').forEach(function(button) {
        button.addEventListener('click', function() {
          const content = this.nextElementSibling;
          if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            this.querySelector('svg').style.transform = 'rotate(180deg)';
          } else {
            content.classList.add('hidden');
            this.querySelector('svg').style.transform = 'rotate(0deg)';
          }
        });
      });
    });
  </script>
