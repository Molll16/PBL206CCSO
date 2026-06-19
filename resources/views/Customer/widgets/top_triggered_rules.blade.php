<div class="flex flex-col h-full justify-between select-none">
    <div class="flex justify-between items-center mb-2 border-b border-gray-700/50 pb-1">
        <span class="text-[11px] text-gray-400 font-medium">Most Active Rules</span>
        <span class="text-[10px] text-gray-500 font-medium">Last 24h</span>
    </div>

    <div id="active-rules-container" class="space-y-2 flex-1 flex flex-col justify-center">
        <div class="flex justify-center py-2">
            <div class="w-4 h-4 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchActiveRulesData();

        // Auto-refresh data setiap 10 detik agar tetap sinkron
        setInterval(fetchActiveRulesData, 10000);

        // Sinkronisasi otomatis jika dropdown agen utama kamu berganti
        document.addEventListener('agentChanged', function () {
            fetchActiveRulesData();
        });
    });

    function fetchActiveRulesData() {
        fetch("{{ route('widget.active-rules') }}?t=" + new Date().getTime())
            .then(response => response.json())
            .then(result => {
                const container = document.getElementById('active-rules-container');
                if (!container) return;

                if (!result.success || !result.data || result.data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-2 text-gray-500 text-[10px]">
                            No active security rules triggered recently.
                        </div>
                    `;
                    return;
                }

                // Bersihkan container dan cetak baris baru dari backend
                container.innerHTML = '';
                result.data.forEach(rule => {
                    // Escape karakter tanda kutip agar title HTML tidak rusak
                    const safeDesc = rule.desc.replace(/"/g, '&quot;');

                    container.innerHTML += `
                        <div class="flex items-center justify-between gap-3 text-[11px]">
                            <div class="w-1/2">
                                <p class="text-gray-300 font-medium truncate" title="${safeDesc}">
                                    ${rule.desc}
                                </p>
                            </div>
                            <div class="w-1/3 bg-gray-700/30 h-1.5 rounded-full overflow-hidden">
                                <div class="${rule.color} h-full rounded-full" style="width: ${rule.w};"></div>
                            </div>
                            <div class="w-10 text-right">
                                <span class="text-gray-200 font-semibold font-mono">${Number(rule.count).toLocaleString()}</span>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(err => {
                console.error("Error loading active rules widget:", err);
            });
    }
</script>