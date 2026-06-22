

<div class="h-full flex flex-col">
    <div id="service-status-container"
        class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">
        <div class="text-center py-4 text-xs text-gray-400 italic">
            Menghubungkan ke Syscollector Wazuh...
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById('service-status-container');
        if (!container) return;

        fetch("{{ route('widget-service-status') }}")
            .then(response => {
                if (!response.ok) throw new Error('Network response error');
                return response.json();
            })
            .then(res => {
                container.innerHTML = '';

                // 🛠️ JIKA DATA KOSONG: Tampilkan peringatan murni, card tidak diisi data palsu
                if (!res.success || !res.data || res.data.length === 0) {
                    container.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <span class="text-sm text-yellow-500 font-semibold mb-1">Gagal Sinkronisasi OS</span>
                            <p class="text-[11px] text-textMuted max-w-[250px]">Data proses dari Syscollector kosong. Pastikan modul syscollector aktif di ossec.conf agen Anda.</p>
                        </div>
                    `;
                    return;
                }

                // JIKA DATA AMAN: Render hasil pemindaian asli
                res.data.forEach(service => {
                    let statusBadge = service.status === 'running'
                        ? `<span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">Running</span>`
                        : `<span class="bg-red-500/20 text-red-400 border border-red-500/30 px-2 py-0.5 rounded text-[10px]">Stopped</span>`;

                    const rowHtml = `
                        <div class="flex justify-between items-center bg-surface border border-borderSubtle px-3 py-2 rounded-lg mb-1.5">
                            <div class="min-w-0 mr-3">
                                <p class="text-xs font-medium truncate text-textMain">${service.name}</p>
                                <p class="text-[11px] text-textMuted truncate">port ${service.port}</p>
                            </div>
                            <div class="shrink-0">${statusBadge}</div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', rowHtml);
                });
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `
                    <div class="text-center py-12 text-xs text-red-400 italic">
                        Gagal memuat modul status backend.
                    </div>`;
            });
    });
</script>