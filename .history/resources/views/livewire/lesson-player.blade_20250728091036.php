<div><div class="text-center">
  <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-lesson-modal" data-hs-overlay="#hs-lesson-modal">
    Dersleri Göster
  </button>
</div>

<div id="hs-lesson-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-lesson-modal-label">
  <div class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all w-full h-full m-0 sm:mx-auto">
    <div class="max-h-full overflow-hidden flex flex-col bg-white border border-gray-200 shadow-2xs rounded-none pointer-events-auto h-full">
      <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
        <h3 id="hs-lesson-modal-label" class="font-bold text-gray-800">
          Ders İçeriği
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#hs-lesson-modal">
          <span class="sr-only">Close</span>
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>

      <div class="flex flex-1 overflow-hidden">
        <!-- Ders Listesi (Sol Taraf) -->
        <div class="w-1/3 border-r border-gray-200 overflow-y-auto">
          <div class="p-4">
            <h4 class="mb-4 text-sm font-semibold uppercase text-gray-600">
              Dersler
            </h4>

            <div class="space-y-2">
              <!-- Ders Öğesi 1 (Aktif) -->
              <a href="#" class="flex items-center gap-x-3 p-3 rounded-lg bg-blue-50 border border-blue-200">
                <div class="flex items-center justify-center size-8 rounded-full bg-blue-100 text-blue-800">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                </div>
                <div class="grow">
                  <h5 class="text-sm font-medium text-blue-800">Giriş ve Temel Kavramlar</h5>
                  <p class="text-xs text-gray-500">12 dakika</p>
                </div>
                <div class="shrink-0">
                  <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <span class="size-1.5 inline-block rounded-full bg-blue-800"></span>
                    Devam Ediyor
                  </span>
                </div>
              </a>

              <!-- Ders Öğesi 2 -->
              <a href="#" class="flex items-center gap-x-3 p-3 rounded-lg hover:bg-gray-100">
                <div class="flex items-center justify-center size-8 rounded-full bg-gray-100 text-gray-800">
                  <span class="text-xs font-medium">2</span>
                </div>
                <div class="grow">
                  <h5 class="text-sm font-medium text-gray-800">Kurulum ve Ayarlar</h5>
                  <p class="text-xs text-gray-500">18 dakika</p>
                </div>
              </a>

              <!-- Ders Öğesi 3 -->
              <a href="#" class="flex items-center gap-x-3 p-3 rounded-lg hover:bg-gray-100">
                <div class="flex items-center justify-center size-8 rounded-full bg-gray-100 text-gray-800">
                  <span class="text-xs font-medium">3</span>
                </div>
                <div class="grow">
                  <h5 class="text-sm font-medium text-gray-800">Temel Fonksiyonlar</h5>
                  <p class="text-xs text-gray-500">22 dakika</p>
                </div>
              </a>

              <!-- Ders Öğesi 4 -->
              <a href="#" class="flex items-center gap-x-3 p-3 rounded-lg hover:bg-gray-100">
                <div class="flex items-center justify-center size-8 rounded-full bg-gray-100 text-gray-800">
                  <span class="text-xs font-medium">4</span>
                </div>
                <div class="grow">
                  <h5 class="text-sm font-medium text-gray-800">İleri Seviye Kullanım</h5>
                  <p class="text-xs text-gray-500">15 dakika</p>
                </div>
              </a>

              <!-- Ders Öğesi 5 -->
              <a href="#" class="flex items-center gap-x-3 p-3 rounded-lg hover:bg-gray-100">
                <div class="flex items-center justify-center size-8 rounded-full bg-gray-100 text-gray-800">
                  <span class="text-xs font-medium">5</span>
                </div>
                <div class="grow">
                  <h5 class="text-sm font-medium text-gray-800">Proje Uygulaması</h5>
                  <p class="text-xs text-gray-500">30 dakika</p>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- Ders İçeriği (Sağ Taraf) -->
        <div class="flex-1 overflow-y-auto p-6">
          <div class="max-w-4xl mx-auto">
            <div class="video-container mb-6">
              @if($videoType === 'youtube' && $videoId)
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                  <iframe class="w-full h-96"
                          src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                          frameborder="0"
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                          allowfullscreen></iframe>
                </div>
              @elseif($lesson->embed_code)
                <div class="aspect-w-16 aspect-h-9">
                  {!! $lesson->embed_code !!}
                </div>
              @else
                <div class="bg-gray-100 rounded-lg p-8 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                </div>
              @endif
            </div>

            <div class="flex justify-between items-center mb-6">
              <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
              </div>

              <div class="flex items-center gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                  Önceki
                </button>

                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                  Sonraki
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
              </div>
            </div>

            <div class="lesson-content bg-gray-50 rounded-lg p-6">
              <h4 class="font-semibold text-gray-900 mb-4">Ders Açıklaması</h4>
              <p class="text-gray-700">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>

              <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-900 mb-3">İlerleme Durumu</h4>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Dersin %45'i tamamlandı</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-between items-center gap-x-2 py-3 px-4 border-t border-gray-200">
        <div class="text-sm text-gray-600">
          <span class="font-medium">1/5</span> Ders Tamamlandı
        </div>
        <div class="flex items-center gap-x-2">
          <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
            Dersi Tamamla
          </button>
          <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-lesson-modal">
            Kapat
          </button>
        </div>
      </div>
    </div>
  </div>
</div>


</div>
<script src="https://cdn.jsdelivr.net/npm/preline@1.8.0/dist/preline.min.js"></script>

<script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.querySelectorAll('.hs-overlay').forEach((el) => HSOverlay.open(el));
    });
  });
</script>
