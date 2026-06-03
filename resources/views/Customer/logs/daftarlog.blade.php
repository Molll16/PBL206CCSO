<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        page: '#121318',
                        surface: '#1a1b23',
                        borderSubtle: '#262833',
                        textMain: '#f1f3f9',
                        textMuted: '#787f99',
                        brand: '#6366f1',
                        brandHover: '#4f46e5'
                    }
                }
            }
        }
    </script>

</head>

<body class="min-h-screen bg-page text-textMain font-sans antialiased">

    @include('Customer.components.header')

    {{-- Tab Nav --}}
    <div class="nav-bar px-6 flex items-center justify-between animate-fade-in">
        <div class="flex gap-8">
            <a href="{{ route('daftarlog') }}"
                class="py-3 text-brand text-sm border-b-2 border-brand font-medium">
                View Logs
            </a>
        </div>
    </div>

    <main class="p-6 mx-auto">

        {{-- Top Stats --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

            {{-- Pie Chart --}}
            <div class="lg:col-span-4 bg-surface border border-borderSubtle rounded-xl p-5 flex flex-col items-center justify-center min-h-[260px] hover:border-brand/30 transition-all">
                <p class="text-sm font-semibold uppercase tracking-wider text-textMuted self-start mb-4 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-brand"></i> Alert Distribution
                </p>
                <div class="w-full max-w-[180px] relative flex items-center justify-center">
                    <canvas id="alertPieChart"></canvas>
                </div>
            </div>

            {{-- Severity Cards --}}
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="bg-surface border border-red-900/50 rounded-xl p-5 flex flex-col justify-between hover:border-red-500/30 transition-all group">
                    <p class="text-xs font-semibold uppercase tracking-wider text-red-400 flex items-center justify-between">
                        Critical <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                    </p>
                    <h2 class="text-4xl font-bold text-red-500 mt-2 tracking-tight">{{ $criticalAlerts ?? 0 }}</h2>
                </div>

                <div class="bg-surface border border-red-800/40 rounded-xl p-5 flex flex-col justify-between hover:border-red-400/30 transition-all group">
                    <p class="text-xs font-semibold uppercase tracking-wider text-red-300 flex items-center justify-between">
                        High <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                    </p>
                    <h2 class="text-4xl font-bold text-red-400 mt-2 tracking-tight">{{ $highAlerts ?? 0 }}</h2>
                </div>

                <div class="bg-surface border border-yellow-700/40 rounded-xl p-5 flex flex-col justify-between hover:border-yellow-500/30 transition-all group">
                    <p class="text-xs font-semibold uppercase tracking-wider text-yellow-300 flex items-center justify-between">
                        Medium <span class="w-2 h-2 rounded-full bg-yellow-300 animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                    </p>
                    <h2 class="text-4xl font-bold text-yellow-400 mt-2 tracking-tight">{{ $mediumAlerts ?? 0 }}</h2>
                </div>

                <div class="bg-surface border border-green-800/40 rounded-xl p-5 flex flex-col justify-between hover:border-green-500/30 transition-all group">
                    <p class="text-xs font-semibold uppercase tracking-wider text-green-300 flex items-center justify-between">
                        Low <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                    </p>
                    <h2 class="text-4xl font-bold text-green-400 mt-2 tracking-tight">{{ $lowAlerts ?? 0 }}</h2>
                </div>

            </div>
        </div>

        {{-- Table --}}
        <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">

            {{-- Table Header --}}
            <div class="p-4 flex items-center justify-between border-b border-borderSubtle bg-page/30">
                <div class="text-xs font-bold uppercase tracking-wider text-textMuted flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4 text-brand"></i>
                    Alerts Logs
                    <span class="text-[10px] bg-page border border-borderSubtle px-2 py-0.5 rounded-full">{{ $alerts->count() }}</span>
                </div>
            </div>

            {{-- Filters --}}
            <div class="p-4 border-b border-borderSubtle bg-page/10 flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                <div class="flex flex-col md:flex-row gap-3">
                    <select id="filterSeverity" class="bg-page border border-borderSubtle text-textMuted rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-brand cursor-pointer">
                        <option value="all">All Severity</option>
                        <option value="critical">Critical</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <input type="date" id="filterDate" class="bg-page border border-borderSubtle text-textMuted rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-brand">
                </div>

                <div class="flex gap-3">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-3/4 -translate-y-1/2 w-3.5 h-3.5 text-textMuted pointer-events-none"></i>
                        <input type="text" id="searchAlert" placeholder="Search alerts..."
                            class="pl-9 pr-4 py-2 bg-page border border-borderSubtle text-textMain rounded-lg text-xs focus:outline-none focus:border-brand/50 transition w-64 placeholder:text-textMuted">
                    </div>
            
                    <button onclick="location.reload()"
                        class="bg-brand hover:bg-brandHover text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-lg shadow-brand/20 transition-all duration-200 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Refresh
                    </button>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm border-collapse">
                    <thead>
                        <tr class="bg-surface text-textMuted text-[11px] font-bold uppercase tracking-wider border-b border-borderSubtle">
                            <th class="p-4">Time</th>
                            <th class="p-4">Severity</th>
                            <th class="p-4">Agent</th>
                            <th class="p-4">Affected User</th>
                            <th class="p-4 text-left">Description</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="alertTableBody" class="divide-y divide-borderSubtle bg-page/30 text-xs">
                        @forelse($alerts as $alert)
                            @php
                                $lvl = (int) ($alert['level'] ?? 0);
                                if ($lvl >= 13) {
                                    $label = 'critical';
                                    $labelText = 'Critical';
                                    $colorClass = 'bg-red-500/10 text-red-500 border-red-500/20';
                                } elseif ($lvl >= 10) {
                                    $label = 'high';
                                    $labelText = 'High';
                                    $colorClass = 'bg-red-400/10 text-red-400 border-red-400/20';
                                } elseif ($lvl >= 5) {
                                    $label = 'medium';
                                    $labelText = 'Medium';
                                    $colorClass = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                                } else {
                                    $label = 'low';
                                    $labelText = 'Low';
                                    $colorClass = 'bg-green-500/10 text-green-400 border-green-500/20';
                                }
                                $alertDate = isset($alert['time']) ? date('Y-m-d', strtotime($alert['time'])) : '';
                            @endphp
                            <tr class="hover:bg-surface/60 transition-colors alert-row" data-severity="{{ $label }}" data-date="{{ $alertDate }}">
                                <td class="p-4 text-textMuted font-mono">
                                    {{ isset($alert['time']) ? date('H:i:s', strtotime($alert['time'])) : '00:00' }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $colorClass }}">
                                        {{ $labelText }} ({{ $lvl }})
                                    </span>
                                </td>
                                <td class="p-4 font-semibold text-textMain">{{ $alert['agent']['name'] ?? 'unknown' }}</td>
                                <td class="p-4 text-textMuted font-mono text-[11px]">{{ $alert['user'] ?? 'unknown' }}</td>
                                <td class="p-4 max-w-xs truncate text-left search-target text-textMain"
                                    title="{{ $alert['description'] }}">
                                    {{ $alert['description'] }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lvl >= 10 ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-green-500/10 text-green-400 border border-green-500/20' }}">
                                        {{ $lvl >= 10 ? '● Active' : '● Resolved' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <button onclick="openModal({{ json_encode($alert) }})"
                                        class="text-brand hover:text-brandHover font-medium hover:underline">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-8 text-textMuted text-sm italic">
                                    No alerts logs recorded today.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="emptyFilterRow" class="hidden">
                            <td colspan="7" class="text-center p-8 text-textMuted text-sm italic">
                                No data matches the selected filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 flex items-center justify-between text-xs text-textMuted border-t border-borderSubtle bg-surface/50">
                <div class="flex items-center gap-2">
                    Rows per page:
                    <select class="bg-page border border-borderSubtle text-textMuted rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-brand text-xs cursor-pointer">
                        <option>10</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button class="hover:text-textMain transition px-1"><i data-lucide="chevron-left" class="w-2 h-2"></i></button>
                    <span class="text-brand font-bold bg-brand/10 border border-brand/20 px-2 py-0.5 rounded">1</span>
                    <button class="hover:text-textMain transition px-1"><i data-lucide="chevron-right" class="w-2 h-2"></i></button>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal --}}
    <div id="alertModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-card rounded-2xl w-full max-w-lg max-h-[650px] flex flex-col animate-fade-in">

            <div class="flex items-center justify-between px-5 py-4 border-b border-borderSubtle shrink-0">
                <div class="flex items-center gap-3">
                    <div id="modalIconContainer" class="w-10 h-10 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 id="modalTitle" class="text-sm font-semibold text-textMain truncate max-w-[320px]">Alert Detail</h2>
                        <p class="text-[11px] text-textMuted">Security Event Detail</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-textMuted hover:text-textMain transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-5 space-y-4 overflow-y-auto flex-grow no-scrollbar">
                <div class="flex items-center gap-2 flex-wrap">
                    <span id="modalSeverity" class="px-2 py-0.5 rounded-full text-[10px] font-bold">LEVEL</span>
                    <span id="modalStatus" class="px-2 py-0.5 rounded-full text-[10px]">Active</span>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between border-b border-borderSubtle pb-1.5">
                        <span class="text-textMuted">Timestamp Log</span>
                        <span id="modalTime" class="text-textMain font-mono">-</span>
                    </div>
                    <div class="flex justify-between border-b border-borderSubtle pb-1.5">
                        <span class="text-textMuted">Target Agent</span>
                        <span id="modalAgent" class="text-textMain">-</span>
                    </div>
                    <div class="flex justify-between border-b border-borderSubtle pb-1.5">
                        <span class="text-textMuted">Agent ID</span>
                        <span id="modalAgentId" class="text-textMuted font-mono">-</span>
                    </div>
                    <div class="flex justify-between pb-1.5">
                        <span class="text-textMuted">Affected User</span>
                        <span id="modalUser" class="text-yellow-400 font-mono">-</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-semibold text-brand mb-1.5">Description</h3>
                    <div class="modal-desc-block rounded-lg p-3">
                        <p id="modalDesc" class="text-xs text-textMuted leading-relaxed">-</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-semibold text-brand mb-1.5">Mapped Alert Object (JSON)</h3>
                    <div class="modal-code-block rounded-lg p-3 max-h-[180px] overflow-auto">
                        <pre id="modalRaw" class="text-green-400 text-[10px] font-mono leading-tight">-</pre>
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 border-t border-borderSubtle flex items-center justify-end shrink-0">
                <button onclick="closeModal()"
                    class="px-3 py-1.5 rounded-lg text-textMuted hover:text-textMain transition text-xs"
                    style="border: 1px solid #262833; background: rgba(255,255,255,0.03);">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // 1. FUNGSI DETAIL POP-UP MODAL DINAMIS
        function openModal(alert) {
            const modal = document.getElementById('alertModal');

            document.getElementById('modalTitle').innerText = alert.description || 'Alert Detail';
            document.getElementById('modalTime').innerText = alert.time ?? '-';
            document.getElementById('modalAgent').innerText = alert.agent?.name ?? 'unknown';
            document.getElementById('modalAgentId').innerText = alert.agent?.id ?? '-';
            document.getElementById('modalUser').innerText = alert.user ?? 'unknown';
            document.getElementById('modalDesc').innerText = alert.description || '-';
            document.getElementById('modalRaw').innerText = JSON.stringify(alert, null, 4);

            const lvl = parseInt(alert.level ?? 0);
            const sevBadge = document.getElementById('modalSeverity');
            const statusBadge = document.getElementById('modalStatus');
            const iconContainer = document.getElementById('modalIconContainer');

            if (lvl >= 13) {
                sevBadge.innerText = 'CRITICAL (LVL ' + lvl + ')';
                sevBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-600/20 border border-red-600 text-red-500';
                iconContainer.className = 'w-10 h-10 rounded-xl bg-red-500/10 border border-red-500 flex items-center justify-center text-red-500';
            } else if (lvl >= 10) {
                sevBadge.innerText = 'HIGH (LVL ' + lvl + ')';
                sevBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-400/20 border border-red-400 text-red-400';
                iconContainer.className = 'w-10 h-10 rounded-xl bg-red-400/10 border border-red-400 flex items-center justify-center text-red-400';
            } else if (lvl >= 5) {
                sevBadge.innerText = 'MEDIUM (LVL ' + lvl + ')';
                sevBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-500/20 border border-yellow-500 text-yellow-400';
                iconContainer.className = 'w-10 h-10 rounded-xl bg-yellow-500/10 border border-yellow-500 flex items-center justify-center text-yellow-400';
            } else {
                sevBadge.innerText = 'LOW (LVL ' + lvl + ')';
                sevBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/20 border border-green-500 text-green-400';
                iconContainer.className = 'w-10 h-10 rounded-xl bg-green-500/10 border border-green-500 flex items-center justify-center text-green-400';
            }

            statusBadge.innerText = lvl >= 10 ? 'Active Incident' : 'Resolved';
            statusBadge.className = `px-2 py-0.5 rounded-full text-[10px] ${lvl >= 10 ? 'bg-red-500/10 border border-red-500/50 text-red-400' : 'bg-green-500/10 border border-green-500/50 text-green-400'}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            lucide.createIcons();
        }

        function closeModal() {
            const modal = document.getElementById('alertModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('alertModal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });


        // 2. FUNGSI LIVE FILTER SEARCH, SEVERITY & KALENDER
        const filterSeverity = document.getElementById('filterSeverity');
        const filterDate = document.getElementById('filterDate');
        const searchAlert = document.getElementById('searchAlert');
        const emptyFilterRow = document.getElementById('emptyFilterRow');

        function filterData() {
            const selectedSeverity = filterSeverity.value.toLowerCase();
            const selectedDate = filterDate.value;
            const searchQuery = searchAlert.value.toLowerCase();

            const rows = document.querySelectorAll('.alert-row');
            let visibleRowsCount = 0;

            rows.forEach(row => {
                const rowSeverity = row.getAttribute('data-severity');
                const rowDate = row.getAttribute('data-date');

                const cellsText = row.querySelector('.search-target').innerText.toLowerCase() + ' ' +
                    row.children[2].innerText.toLowerCase() + ' ' +
                    row.children[3].innerText.toLowerCase();

                const matchSeverity = (selectedSeverity === 'all' || rowSeverity === selectedSeverity);
                const matchDate = (!selectedDate || rowDate === selectedDate);
                const matchSearch = (!searchQuery || cellsText.includes(searchQuery));

                if (matchSeverity && matchDate && matchSearch) {
                    row.classList.remove('hidden');
                    visibleRowsCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (visibleRowsCount === 0 && rows.length > 0) {
                emptyFilterRow.classList.remove('hidden');
            } else {
                emptyFilterRow.classList.add('hidden');
            }
        }

        filterSeverity.addEventListener('change', filterData);
        filterDate.addEventListener('change', filterData);
        searchAlert.addEventListener('input', filterData);

        function clearFilters() {
            filterSeverity.value = 'all';
            filterDate.value = '';
            searchAlert.value = '';
            filterData();
        }


        // 3. CHART.JS CONFIGURATION (DOUGHNUT AMBIL DARI BACKEND)
        const criticalCount = {{ $criticalAlerts ?? 0 }};
        const highCount = {{ $highAlerts ?? 0 }};
        const mediumCount = {{ $mediumAlerts ?? 0 }};
        const lowCount = {{ $lowAlerts ?? 0 }};
        const totalAlerts = criticalCount + highCount + mediumCount + lowCount;

        const ctx = document.getElementById('alertPieChart').getContext('2d');
        const hasData = totalAlerts > 0;
        const dataSet = hasData ? [criticalCount, highCount, mediumCount, lowCount] : [1];
        const colorSet = hasData ? ['#ef4444', '#f87171', '#facc15', '#4ade80'] : ['#262833'];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: dataSet,
                    backgroundColor: colorSet,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: hasData }
                },
                responsive: true,
                maintainAspectRatio: true
            }
        });
    </script>
</body>

</html>