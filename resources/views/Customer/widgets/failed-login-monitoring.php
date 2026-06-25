
<div class="flex flex-col h-full justify-between select-none p-1">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
            </path>
        </svg>
        <h2 class="text-sm font-medium text-gray-400 tracking-wide">Failed Login Monitoring</h2>
    </div>

    <div class="my-auto py-2">
        <span id="failed-login-count" class="text-4xl font-bold text-amber-500 font-sans tracking-tight block">
            0
        </span>
        <span id="failed-login-timeline" class="text-gray-400 text-[11px] font-normal block mt-1">
            Menghubungkan ke agen...
        </span>
    </div>

    <div class="self-start">
        <span id="failed-login-status"
            class="px-2 py-0.5 bg-gray-500/10 border border-gray-500/30 text-gray-400 rounded-md text-[10px] font-semibold tracking-wide">
            Scanning
        </span>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchFailedLoginsData();
        setInterval(fetchFailedLoginsData, 10000);
    });

    function fetchFailedLoginsData() {
        fetch("{{ route('widget.failed-login-monitoring') }}")
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const countEl = document.getElementById('failed-login-count');
                    const timelineEl = document.getElementById('failed-login-timeline');
                    const statusBadge = document.getElementById('failed-login-status');

                    if (countEl) countEl.innerText = Number(result.data.count).toLocaleString();
                    if (timelineEl) timelineEl.innerText = result.data.timeline;
                    if (statusBadge) {
                        statusBadge.innerText = result.data.status_tag;

                        if (result.data.status_tag === 'Spike Detected') {
                            statusBadge.className = "px-2 py-0.5 bg-red-500/10 border border-red-500/30 text-red-400 rounded-md text-[10px] font-semibold tracking-wide";
                        } else if (result.data.status_tag === 'Warning') {
                            statusBadge.className = "px-2 py-0.5 bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 rounded-md text-[10px] font-semibold tracking-wide";
                        } else {
                            statusBadge.className = "px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-md text-[10px] font-semibold tracking-wide";
                        }
                    }
                }
            })
            .catch(error => console.error("Kendala sinkronisasi widget:", error));
    }
</script>