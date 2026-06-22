{{-- resources/views/Customer/widgets/network-traffic.blade.php --}}

<div class="h-full flex flex-col">

    {{-- Stats Atas --}}
    <div class="grid grid-cols-2 gap-2 mb-2 flex-shrink-0">
        <div class="bg-white/5 rounded-lg px-3 py-2">
            <p class="text-[10px] text-gray-400 mb-0.5">Inbound</p>
            <p class="text-sm font-semibold text-white">
                <span id="net-inbound">0.0</span> <span class="text-[10px] text-gray-400 font-normal">Gb</span>
            </p>
        </div>
        <div class="bg-white/5 rounded-lg px-3 py-2">
            <p class="text-[10px] text-gray-400 mb-0.5">Outbound</p>
            <p class="text-sm font-semibold text-white">
                <span id="net-outbound">0.0</span> <span class="text-[10px] text-gray-400 font-normal">Gb</span>
            </p>
        </div>
    </div>

    {{-- List Interface Bawah --}}
    <div id="network-interfaces-container"
        class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">
        <div class="text-center py-4 text-xs text-gray-400 italic">
            Memindai kartu jaringan server...
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById('network-interfaces-container');
        if (!container) return;

        fetch("{{ route('widget-network-traffic') }}")
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(res => {
                container.innerHTML = '';

                // PERBAIKAN VALIDASI: Cek ketersediaan array interfaces di dalam objek data
                if (!res.success || !res.data || !res.data.interfaces || res.data.interfaces.length === 0) {
                    container.innerHTML = `<div class="text-center py-4 text-xs text-yellow-500 italic">⚠️ Jaringan tidak tersinkronisasi. Pastikan modul Syscollector aktif.</div>`;

                    // Setel default angka atas ke 0.0 jika data kosong
                    document.getElementById('net-inbound').innerText = "0.0";
                    document.getElementById('net-outbound').innerText = "0.0";
                    return;
                }

                // Update info stats global atas jika data ada
                document.getElementById('net-inbound').innerText = res.data.stats.inbound ?? "0.0";
                document.getElementById('net-outbound').innerText = res.data.stats.outbound ?? "0.0";

                // Render list interface asli dari OS
                res.data.interfaces.forEach(iface => {
                    let badge = '';

                    if (iface.direction === 'up') {
                        badge = `<span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">↑ ${iface.speed}</span>`;
                    } else {
                        badge = `<span class="bg-white/5 text-gray-500 border border-white/10 px-2 py-0.5 rounded text-[10px]">✕ Down</span>`;
                    }

                    const rowHtml = `
                        <div class="flex justify-between items-center bg-white/5 px-3 py-2 rounded-lg">
                            <div class="min-w-0 mr-3">
                                <p class="text-xs font-medium truncate text-white">${iface.name}</p>
                                <p class="text-[11px] text-gray-400 truncate">${iface.ip}</p>
                            </div>
                            <div class="shrink-0">${badge}</div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', rowHtml);
                });
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `<div class="text-center py-4 text-xs text-red-400 italic">⚠️ Gagal memuat modul jaringan backend.</div>`;
            });
    });
</script>