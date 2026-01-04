<div class="w-full">
  @php
    use Illuminate\Support\Carbon;
  @endphp
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce
  @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      let currentMap = document.getElementById('currentMap');
      let map = document.getElementById('map');

      setTimeout(() => {
        toggleMap();
        toggleCurrentMap();
      }, 1000);

      function toggleCurrentMap() {
        const mapIsVisible = currentMap.style.display === "none";
        currentMap.style.display = mapIsVisible ? "block" : "none";
        document.querySelector('#toggleCurrentMap').innerHTML = mapIsVisible ?
          `<x-heroicon-s-chevron-up class="mr-2 h-5 w-5" />` :
          `<x-heroicon-s-chevron-down class="mr-2 h-5 w-5" />`;
      }

      function toggleMap() {
        const mapIsVisible = map.style.display === "none";
        map.style.display = mapIsVisible ? "block" : "none";
      }
    </script>
  @endpushOnce

  @if (!$isAbsence)
    <script src="{{ url('/assets/js/html5-qrcode.min.js') }}"></script>
  @endif

  <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
    @if (!$isAbsence)
      <div class="flex flex-col gap-6 shrink-0 lg:w-[400px]">
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-xl shadow-2xl">
          <x-select id="shift" class="block w-full mb-4 bg-slate-900/50 border-white/10 text-white focus:border-blue-500 focus:ring-blue-500 rounded-xl" wire:model="shift_id" disabled="{{ !is_null($attendance) }}">
            <option value="">{{ __('Pilih Shift Kerja') }}</option>
            @foreach ($shifts as $shift)
              <option value="{{ $shift->id }}" {{ $shift->id == $shift_id ? 'selected' : '' }}>
                {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
              </option>
            @endforeach
          </x-select>
          @error('shift_id')
            <x-input-error for="shift" class="mb-2" message={{ $message }} />
          @enderror

          <div class="relative w-full aspect-square rounded-2xl overflow-hidden ring-1 ring-white/20 shadow-inner bg-black" wire:ignore>
            <div id="scanner" class="w-full h-full"></div>
            <!-- Overlay Guide -->
            <div class="absolute inset-0 border-2 border-white/20 pointer-events-none rounded-2xl">
               <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-blue-500/50 rounded-xl"></div>
            </div>
            <div class="absolute bottom-4 left-0 right-0 text-center">
                 <span class="px-3 py-1 bg-black/50 text-white/70 text-xs rounded-full backdrop-blur-md">Arahkan QR Code ke sini</span>
            </div>
          </div>
        </div>
      </div>
    @endif

    <div class="flex-1 w-full space-y-6">
      <!-- Alerts -->
      <h4 id="scanner-error" class="text-center md:text-left text-lg font-semibold text-red-400 bg-red-500/10 border border-red-500/20 p-3 rounded-xl empty:hidden" wire:ignore></h4>
      <h4 id="scanner-result" class="hidden text-center md:text-left text-lg font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 p-3 rounded-xl">
        {{ $successMsg }}
      </h4>

      <!-- Header Info -->
      <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-xl shadow-lg">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                 <h2 class="text-2xl font-bold text-white tracking-tight">{{ now()->translatedFormat('l, d F Y') }}</h2>
                 <p class="text-slate-400 mt-1 flex items-center gap-2">
                    <x-heroicon-o-map-pin class="w-4 h-4" />
                    @if (!is_null($currentLiveCoords))
                        <a href="{{ \App\Helpers::getGoogleMapsUrl($currentLiveCoords[0], $currentLiveCoords[1]) }}" target="_blank"
                        class="hover:text-blue-400 transition-colors underline decoration-blue-500/30">
                        {{ $currentLiveCoords[0] . ', ' . $currentLiveCoords[1] }}
                        </a>
                    @else
                        <span>Mendeteksi lokasi...</span>
                    @endif
                 </p>
            </div>
            <button class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-300 rounded-xl transition-all text-sm font-medium border border-white/5" onclick="toggleCurrentMap()" id="toggleCurrentMap">
                <span>Peta</span>
                <x-heroicon-s-chevron-down class="h-4 w-4" />
            </button>
          </div>
          
          <div class="mt-4 h-48 w-full rounded-2xl overflow-hidden ring-1 ring-white/10 shadow-inner bg-slate-900 relative z-0" id="currentMap" wire:ignore></div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Absen Masuk -->
        <div class="relative bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm overflow-hidden group hover:bg-white/10 transition-all">
            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $attendance?->status == 'late' ? 'bg-red-500' : 'bg-blue-500' }}"></div>
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Absen Masuk</h4>
                <div class="p-2 {{ $attendance?->status == 'late' ? 'bg-red-500/20 text-red-500' : 'bg-blue-500/20 text-blue-500' }} rounded-lg">
                    <x-heroicon-m-arrow-right-end-on-rectangle class="w-5 h-5" />
                </div>
            </div>
            <div class="text-2xl font-bold text-white mt-1">
                @if ($isAbsence)
                  {{ __($attendance?->status) ?? '-' }}
                @else
                  {{ $attendance?->time_in ? Carbon::parse($attendance?->time_in)->format('H:i') : '--:--' }}
                @endif
            </div>
            <div class="text-xs text-slate-500 mt-1">
                 {{ $attendance?->status == 'late' ? 'Status: Terlambat' : ($attendance?->time_in ? 'Tepat Waktu' : 'Belum absen') }}
            </div>
        </div>

        <!-- Absen Keluar -->
        <div class="relative bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm overflow-hidden group hover:bg-white/10 transition-all">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500"></div>
            <div class="flex justify-between items-start mb-2">
                <h4 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Absen Keluar</h4>
                <div class="p-2 bg-orange-500/20 text-orange-500 rounded-lg">
                    <x-heroicon-m-arrow-left-start-on-rectangle class="w-5 h-5" />
                </div>
            </div>
            <div class="text-2xl font-bold text-white mt-1">
                @if ($isAbsence)
                  {{ __($attendance?->status) ?? '-' }}
                @else
                  {{ $attendance?->time_out ? Carbon::parse($attendance?->time_out)->format('H:i') : '--:--' }}
                @endif
            </div>
             <div class="text-xs text-slate-500 mt-1">
                 {{ $attendance?->time_out ? 'Selesai bekerja' : 'Belum absen' }}
            </div>
        </div>

        <!-- Koordinat -->
        <button class="relative bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm overflow-hidden group hover:bg-white/10 transition-all text-left w-full"
            {{ is_null($attendance?->lat_lng) ? 'disabled' : 'onclick=toggleMap()' }} id="toggleMap">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-purple-500"></div>
             <div class="flex justify-between items-start mb-2">
                <h4 class="text-slate-400 font-medium text-sm uppercase tracking-wider">Koordinat</h4>
                <div class="p-2 bg-purple-500/20 text-purple-500 rounded-lg">
                    <x-heroicon-m-map-pin class="w-5 h-5" />
                </div>
            </div>
            <div class="text-sm font-semibold text-white mt-2 truncate w-full">
                @if (is_null($attendance?->lat_lng))
                  -- , --
                @else
                   {{ Str::limit($attendance?->latitude . ', ' . $attendance?->longitude, 20) }}
                @endif
            </div>
             <div class="text-xs text-slate-500 mt-1">Klik untuk lihat peta</div>
        </button>
      </div>

       <div class="h-0 overflow-hidden transition-all duration-300" id="map" wire:ignore></div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-2 gap-4" wire:ignore>
        <a href="{{ route('apply-leave') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-4 shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
             <div class="relative z-10 flex flex-col items-center justify-center gap-2 text-center">
                 <div class="p-2 bg-white/20 rounded-full">
                     <x-heroicon-m-envelope-open class="h-6 w-6 text-white" />
                 </div>
                 <span class="font-bold text-white text-sm sm:text-base">Ajukan Izin</span>
             </div>
             <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent group-hover:opacity-0 transition-opacity"></div>
        </a>

        <a href="{{ route('attendance-history') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 p-4 shadow-lg shadow-blue-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
             <div class="relative z-10 flex flex-col items-center justify-center gap-2 text-center">
                  <div class="p-2 bg-white/20 rounded-full">
                     <x-heroicon-m-clock class="h-6 w-6 text-white" />
                 </div>
                 <span class="font-bold text-white text-sm sm:text-base">Riwayat</span>
             </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent group-hover:opacity-0 transition-opacity"></div>
        </a>
      </div>
    </div>
  </div>
</div>

@script
  <script>
    const errorMsg = document.querySelector('#scanner-error');
    getLocation();

    async function getLocation() {
      if (navigator.geolocation) {
        const map = L.map('currentMap');
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 21,
        }).addTo(map);
        navigator.geolocation.watchPosition((position) => {
          console.log(position);
          $wire.$set('currentLiveCoords', [position.coords.latitude, position.coords.longitude]);
          map.setView([
            Number(position.coords.latitude),
            Number(position.coords.longitude),
          ], 13);
          L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        }, (err) => {
          console.error(`ERROR(${err.code}): ${err.message}`);
          alert('{{ __('Please enable your location') }}');
        });
      } else {
        document.querySelector('#scanner-error').innerHTML = "Gagal mendeteksi lokasi";
      }
    }

    if (!$wire.isAbsence) {
      const scanner = new Html5Qrcode('scanner');

      const config = {
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
        fps: 15,
        aspectRatio: 1,
        qrbox: {
          width: 280,
          height: 280
        },
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
      };

      async function startScanning() {
        if (scanner.getState() === Html5QrcodeScannerState.PAUSED) {
          return scanner.resume();
        }
        await scanner.start({
            facingMode: "environment"
          },
          config,
          onScanSuccess,
        );
      }

      async function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code matched = ${decodedText}`, decodedResult);

        if (scanner.getState() === Html5QrcodeScannerState.SCANNING) {
          scanner.pause(true);
        }

        if (!(await checkTime())) {
          await startScanning();
          return;
        }

        const result = await $wire.scan(decodedText);

        if (result === true) {
          return onAttendanceSuccess();
        } else if (typeof result === 'string') {
          errorMsg.innerHTML = result;
        }

        setTimeout(async () => {
          await startScanning();
        }, 500);
      }

      async function checkTime() {
        const attendance = await $wire.getAttendance();

        if (attendance) {
          const timeIn = new Date(attendance.time_in).valueOf();
          const diff = (Date.now() - timeIn) / (1000 * 3600);
          const minAttendanceTime = 1;
          console.log(`Difference = ${diff}`);
          if (diff <= minAttendanceTime) {
            const timeIn = new Date(attendance.time_in).toLocaleTimeString([], {
              hour: 'numeric',
              minute: 'numeric',
              second: 'numeric',
              hour12: false,
            });
            const confirmation = confirm(
              `Anda baru saja absen pada ${timeIn}, apakah ingin melanjutkan untuk absen keluar?`
            );
            return confirmation;
          }
        }
        return true;
      }

      function onAttendanceSuccess() {
        scanner.stop();
        errorMsg.innerHTML = '';
        document.querySelector('#scanner-result').classList.remove('hidden');
      }

      const observer = new MutationObserver((mutationList, observer) => {
        const classes = ['text-white', 'bg-blue-500', 'dark:bg-blue-400', 'rounded-md', 'px-3', 'py-1'];
        for (const mutation of mutationList) {
          if (mutation.type === 'childList') {
            const startBtn = document.querySelector('#html5-qrcode-button-camera-start');
            const stopBtn = document.querySelector('#html5-qrcode-button-camera-stop');
            const fileBtn = document.querySelector('#html5-qrcode-button-file-selection');
            const permissionBtn = document.querySelector('#html5-qrcode-button-camera-permission');

            if (startBtn) {
              startBtn.classList.add(...classes);
              stopBtn.classList.add(...classes, 'bg-red-500');
              fileBtn.classList.add(...classes);
            }

            if (permissionBtn)
              permissionBtn.classList.add(...classes);
          }
        }
      });

      observer.observe(document.querySelector('#scanner'), {
        childList: true,
        subtree: true,
      });

      const shift = document.querySelector('#shift');
      const msg = 'Pilih shift terlebih dahulu';
      let isRendered = false;
      setTimeout(() => {
        if (!shift.value) {
          errorMsg.innerHTML = msg;
        } else {
          startScanning();
          isRendered = true;
        }
      }, 1000);
      shift.addEventListener('change', () => {
        if (!isRendered) {
          startScanning();
          isRendered = true;
          errorMsg.innerHTML = '';
        }
        if (!shift.value) {
          scanner.pause(true);
          errorMsg.innerHTML = msg;
        } else if (scanner.getState() === Html5QrcodeScannerState.PAUSED) {
          scanner.resume();
          errorMsg.innerHTML = '';
        }
      });

      const map = L.map('map').setView([
        Number({{ $attendance?->latitude }}),
        Number({{ $attendance?->longitude }}),
      ], 13);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 21,
      }).addTo(map);
      L.marker([
        Number({{ $attendance?->latitude }}),
        Number({{ $attendance?->longitude }}),
      ]).addTo(map);
    }
  </script>
@endscript
