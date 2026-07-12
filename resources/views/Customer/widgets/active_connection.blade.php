<div class="h-full flex flex-col" id="active-connections-wrapper">
    {{-- ALERT ERROR JUJUR (Hanya muncul jika backend melempar error/offline) --}}
    <div id="conn-error-msg"
        class="text-xs text-red-400 bg-red-500/10 border border-red-500/20 rounded-lg p-2 mb-2 hidden"></div>

    {{-- LIST CONTAINER (Scrollbar disembunyikan otomatis) --}}
    <div id="connections-list"
        class="flex-1 space-y-2 overflow-y-auto [&::-webkit-scrollbar]:hidden [scrollbar-width:none]"
        style="max-height: 400px;">
        <div class="text-center py-5 text-gray-500 text-xs">
            <div class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent text-cyan-400 rounded-full mb-1"
                role="status"></div>
            <p>Membaca aktivitas jaringan...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const listContainer = document.getElementById('connections-list');
        const errorBox = document.getElementById('conn-error-msg');

        fetch("{{ route('widget.active-connections') }}")
            .then(response => response.json())
            .then(res => {
                listContainer.innerHTML = '';

                // 1. FALLBACK SERVER OFFLINE / RES SUCCESS FALSE
                if (res.success === false) {
                    errorBox.textContent = `Error: ${res.message || 'Gagal tersambung ke server.'}`;
                    errorBox.classList.remove('hidden');
                    listContainer.innerHTML = `<div class="text-center py-6 text-red-400 text-xs font-medium">⚠️ Status: Koneksi Wazuh Terputus</div>`;
                    return;
                }

                // 2. FALLBACK REAL-TIME KOSONG (Wazuh ON, tapi log network memang kosong [])
                if (!res.data || res.data.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-6 text-gray-500 text-xs">
                            <i class="fas fa-check-circle text-emerald-500 mb-1.5 fa-lg"></i>
                            <p class="font-medium text-gray-400">Aman</p>
                            <p class="text-[10px] text-gray-600 mt-0.5">Tidak ada log aktivitas koneksi mencurigakan saat ini.</p>
                        </div>
                    `;
                    return;
                }

                // 3. RENDER DATA MURNI DARI WAZUH
                listContainer.innerHTML = res.data.map(conn => {
                    const styles = {
                        high: { border: 'border-red-500/30 bg-red-500/10', text: 'text-red-400', badge: 'border-red-500/30 text-red-400 bg-red-500/10' },
                        medium: { border: 'border-yellow-500/30 bg-yellow-500/10', text: 'text-yellow-400', badge: 'border-yellow-500/30 text-yellow-400 bg-yellow-500/10' },
                        low: { border: 'border-green-500/30 bg-green-500/10', text: 'text-green-400', badge: 'border-green-500/30 text-green-400 bg-green-500/10' }
                    };

                    const cfg = styles[conn.risk] || styles.low;

                    return `
                        <div class="rounded-lg border px-3 py-2 ${cfg.border}">
                            <div class="flex justify-between items-start">
                                <div class="min-w-0 mr-3">
                                    <p class="text-xs font-medium text-white truncate">${conn.src_ip} → ${conn.dst_ip}</p>
                                    <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                        ${conn.service} (${conn.protocol}) • Status: <span class="${cfg.text}">${conn.status}</span>
                                    </p>
                                    <p class="text-[10px] text-gray-500 mt-1 bg-black/30 p-1.5 rounded border border-gray-800/50 line-height: 1.4; word-break: break-all;">
                                        ${conn.info}
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-[10px] text-gray-400 font-medium">${conn.duration}</p>
                                    <span class="mt-1 inline-block px-1.5 py-0.5 rounded text-[9px] border uppercase ${cfg.badge} font-bold tracking-wider">
                                        ${conn.risk}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            })
            .catch(err => {
                errorBox.textContent = 'Gagal memuat telemetri jaringan: Masalah koneksi lokal.';
                errorBox.classList.remove('hidden');
                listContainer.innerHTML = `<div class="text-center py-6 text-red-400 text-xs">Koneksi Putus</div>`;
            });
    });
</script>