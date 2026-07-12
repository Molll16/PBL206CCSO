{{-- PERBAIKAN: Menambahkan overflow-hidden pada wrapper utama agar ujung peta tidak bocor keluar --}}
<div class="h-full w-full relative rounded-xl overflow-hidden border border-gray-700/60 bg-[#121318]"
    id="geo-attack-wrapper">

    {{-- FLOATING STATUS BADGE --}}
    <div id="map-status-badge"
        class="absolute top-3 right-3 z-[1000] px-3 py-1.5 rounded-lg border border-gray-700 bg-slate-950 text-xs text-gray-300 flex items-center gap-2 shadow-2xl">
        <div id="map-status-spinner"
            class="animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent text-cyan-400 rounded-full">
        </div>
        <span id="map-status-text">Memuat geo-telemetri...</span>
    </div>

    {{-- ALERT ERROR KONEKSI --}}
    <div id="map-error-msg"
        class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg p-2 mb-2 hidden mx-3 mt-3 relative z-[1000]">
    </div>

    {{-- MAP CONTAINER: rounded-xl dilepas dari sini karena sudah ditangani oleh pembungkus luar --}}
    <div id="attackMap" class="w-full h-full" style="min-height: 180px; height: 100%;"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusBadge = document.getElementById('map-status-badge');
        const statusSpinner = document.getElementById('map-status-spinner');
        const statusText = document.getElementById('map-status-text');
        const errorBox = document.getElementById('map-error-msg');

        fetch("{{ route('widget.geo-attacks') }}")
            .then(response => response.json())
            .then(res => {
                if (window.myMap) { setTimeout(() => { window.myMap.invalidateSize(); }, 200); }

                // 1. STATUS OFFLINE
                if (res.success === false) {
                    errorBox.textContent = `Error: ${res.message || 'Gagal terhubung ke server.'}`;
                    errorBox.classList.remove('hidden');
                    statusSpinner.classList.add('hidden');
                    statusBadge.className = "absolute top-3 right-3 z-[1000] px-3 py-1.5 rounded-lg border border-red-500/50 bg-slate-950 text-xs text-red-400 font-bold shadow-2xl";
                    statusText.innerHTML = `⚠️ Server Wazuh Offline`;
                    return;
                }

                // 2. STATUS DATA KOSONG / AMAN
                if (!res.data || res.data.length === 0) {
                    statusSpinner.classList.add('hidden');
                    statusBadge.className = "absolute top-3 right-3 z-[1000] px-3 py-1.5 rounded-lg border border-emerald-500/40 bg-slate-950 text-xs text-emerald-400 font-bold shadow-2xl flex items-center gap-1.5";
                    statusText.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Peta Aman`;
                    return;
                }

                // 3. DATA ADA
                statusBadge.classList.add('hidden');
                window.geoAttacksData = res.data;
            })
            .catch(err => {
                errorBox.textContent = 'Gagal memuat peta: Masalah jaringan lokal.';
                errorBox.classList.remove('hidden');
                statusSpinner.classList.add('hidden');
                statusBadge.className = "absolute top-3 right-3 z-[1000] px-3 py-1.5 rounded-lg border border-red-500/50 bg-slate-950 text-xs text-red-400 font-bold shadow-2xl";
                statusText.innerHTML = `Koneksi Putus`;
            });
    });
</script>