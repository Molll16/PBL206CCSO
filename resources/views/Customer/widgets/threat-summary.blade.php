<div class="bg-gray-900 text-white p-4 rounded-2xl shadow-lg flex flex-col h-full max-h-[500px]">

    <style>
        .custom-scroll-clean::-webkit-scrollbar {
            display: none;
        }

        .custom-scroll-clean {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="space-y-4 overflow-y-auto flex-grow pr-1 custom-scroll-clean">

        <div class="grid grid-cols-3 gap-2 shrink-0">
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-red-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Active</p>
                <p id="threat-active-count" class="text-lg font-bold text-red-500 mt-0.5">0</p>
            </div>
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-yellow-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Pending</p>
                <p id="threat-pending-count" class="text-lg font-bold text-yellow-400 mt-0.5">0</p>
            </div>
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-green-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Resolved</p>
                <p id="threat-resolved-count" class="text-lg font-bold text-green-500 mt-0.5">0</p>
            </div>
        </div>

        <div class="space-y-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Threat Categories</p>
            <div id="threat-categories-container" class="space-y-2">
                <div class="flex justify-center py-4">
                    <div class="w-4 h-4 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>

        <div class="space-y-2 pt-1">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Latest Trend</p>
            <div id="threat-trend-container">
                <div class="bg-gray-800/40 p-3 rounded-xl flex items-center gap-2 text-xs text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                    <span>System state is stable</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchThreatSummaryData();
        // Polling setiap 10 detik biar sinkron otomatis
        setInterval(fetchThreatSummaryData, 10000);

        // Pasang event listener jika sistem dashboard kamu punya fitur ganti agen dropdown
        document.addEventListener('agentChanged', function () {
            fetchThreatSummaryData();
        });
    });

    function fetchThreatSummaryData() {
        // Sesuaikan route name dengan yang ada di web.php untuk getThreatSummary kamu
        fetch("{{ route('widget.threat-summary') }}?t=" + new Date().getTime())
            .then(response => response.json())
            .then(result => {
                if (!result.success || !result.data) return;

                const data = result.data;

                // 1. Update Nilai Counter Atas
                document.getElementById('threat-active-count').innerText = data.active ?? 0;
                document.getElementById('threat-pending-count').innerText = data.pending ?? 0;
                document.getElementById('threat-resolved-count').innerText = data.resolved ?? 0;

                // 2. Update List Kategori
                const categoriesContainer = document.getElementById('threat-categories-container');
                if (categoriesContainer) {
                    if (!data.categories || data.categories.length === 0) {
                        categoriesContainer.innerHTML = `
                            <div class="bg-gray-800/20 border border-dashed border-gray-700 p-4 rounded-xl text-center">
                                <p class="text-xs text-gray-500 font-medium">No recent threats detected on your agents.</p>
                            </div>
                        `;
                    } else {
                        categoriesContainer.innerHTML = '';
                        data.categories.forEach(cat => {
                            let barColor = 'bg-blue-500';
                            if (cat.severity === 'high') barColor = 'bg-red-500';
                            else if (cat.severity === 'medium') barColor = 'bg-yellow-500';

                            categoriesContainer.innerHTML += `
                                <div class="bg-gray-800/40 p-3 rounded-xl space-y-2 border border-gray-800/60">
                                    <div class="flex justify-between text-xs">
                                        <span class="font-medium text-gray-300 truncate max-w-[170px]" title="${cat.name}">
                                            ${cat.name}
                                        </span>
                                        <span class="text-gray-400 font-semibold shrink-0">
                                            ${cat.count} events (${cat.percentage}%)
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full ${barColor}" style="width: ${cat.percentage}%"></div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                }

                // 3. Update Status Trend Paling Bawah
                const trendContainer = document.getElementById('threat-trend-container');
                if (trendContainer) {
                    if ((data.active ?? 0) > 0 || (data.categories && data.categories.length > 0)) {
                        const topThreatName = data.categories[0] ? data.categories[0].name : 'Security Alert Activity';
                        trendContainer.innerHTML = `
                            <div class="bg-gray-800 p-3 rounded-xl flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2 h-2 rounded-full bg-red-400 shrink-0 animate-pulse"></span>
                                    <span class="text-gray-300 truncate">${topThreatName}</span>
                                </div>
                                <span class="text-red-400 font-medium shrink-0 ml-2">Active</span>
                            </div>
                        `;
                    } else {
                        trendContainer.innerHTML = `
                            <div class="bg-gray-800/40 p-3 rounded-xl flex items-center gap-2 text-xs text-gray-400">
                                <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                                <span>System state is stable</span>
                            </div>
                        `;
                    }
                }
            })
            .catch(err => console.error("Error loading threat summary:", err));
    }
</script>