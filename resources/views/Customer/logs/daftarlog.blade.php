<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #2b2d34;
            color: #e5e7eb;
            font-family: 'Inter', sans-serif;
        }

        .bg-header {
            background-color: #1a1c1e;
        }

        .bg-card {
            background-color: #2b2d32;
        }

        .border-custom {
            border-color: #4a4e54;
        }

        .table-row-hover:hover {
            background-color: rgba(255, 255, 255, 0.03);
            transition: all 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</head>

<body class="min-h-screen bg-[#2B2D34]">

    @include('Customer.components.header')

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in">
        <div class="flex gap-8">
            <a href="{{ route('daftarlog') }}"
                class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">View Logs</a>
        </div>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <div
                class="lg:col-span-4 bg-[#2B2D32] border border-custom rounded-xl p-5 flex flex-col items-center justify-center min-h-[260px]">
                <p class="text-sm font-medium text-gray-400 self-start mb-2 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-cyan-400"></i> Alert Distribution
                </p>
                <div class="w-full max-w-[180px] relative flex items-center justify-center">
                    <canvas id="alertPieChart"></canvas>
                </div>
            </div>

            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                    class="bg-[#2B2D32] border border-red-700 rounded-xl p-5 flex flex-col justify-between hover:bg-white/5 transition">
                    <p class="text-sm text-red-400 font-medium flex items-center justify-between">
                        Critical <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    </p>
                    <h2 class="text-4xl font-bold text-red-500 mt-2">{{ $criticalAlerts ?? 0 }}</h2>
                </div>

                <div
                    class="bg-[#2B2D32] border border-red-500 rounded-xl p-5 flex flex-col justify-between hover:bg-white/5 transition">
                    <p class="text-sm text-red-300 font-medium">High</p>
                    <h2 class="text-4xl font-bold text-red-400 mt-2">{{ $highAlerts ?? 0 }}</h2>
                </div>

                <div
                    class="bg-[#2B2D32] border border-yellow-500 rounded-xl p-5 flex flex-col justify-between hover:bg-white/5 transition">
                    <p class="text-sm text-yellow-300 font-medium">Medium</p>
                    <h2 class="text-4xl font-bold text-yellow-400 mt-2">{{ $mediumAlerts ?? 0 }}</h2>
                </div>

                <div
                    class="bg-[#2B2D32] border border-green-500 rounded-xl p-5 flex flex-col justify-between hover:bg-white/5 transition">
                    <p class="text-sm text-green-300 font-medium">Low</p>
                    <h2 class="text-4xl font-bold text-green-400 mt-2">{{ $lowAlerts ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="border border-white rounded-sm bg-transparent overflow-hidden">
            <div class="p-3 flex items-center justify-between border-b border-white">
                <div class="text-sm font-medium flex items-center gap-1">Alerts = {{ $alerts->count() }}</div>
            </div>

            <div class="p-4 border-b border-white flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                <div class="flex flex-col md:flex-row gap-3">
                    <select id="filterSeverity"
                        class="bg-[#2B2D32] border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-400">
                        <option value="all">All Severity</option>
                        <option value="critical">Critical</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <input type="date" id="filterDate"
                        class="bg-[#2B2D32] border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="flex gap-3">
                    <input type="text" id="searchAlert" placeholder="Search alerts..."
                        class="bg-[#2B2D32] border border-gray-600 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-cyan-400">
                    <button onclick="clearFilters()"
                        class="bg-gray-600 hover:bg-gray-700 transition px-4 py-2 rounded-lg text-sm font-medium text-white">
                        Reset
                    </button>
                    <button onclick="location.reload()"
                        class="bg-cyan-500 hover:bg-cyan-600 transition px-4 py-2 rounded-lg text-sm font-medium text-white flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-600 text-center text-gray-300 text-[10px] tracking-wider">
                            <th class="p-3 font-semibold">Time</th>
                            <th class="p-3 font-semibold">Severity</th>
                            <th class="p-3 font-semibold">Agent</th>
                            <th class="p-3 font-semibold">Affected User</th>
                            <th class="p-3 font-semibold">Description</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="alertTableBody" class="divide-y divide-gray-700/50 text-center">
                        @forelse($alerts as $alert)
                            @php
                                $lvl = (int) ($alert['level'] ?? 0);
                                if ($lvl >= 13) {
                                    $label = 'critical';
                                    $labelText = 'Critical';
                                    $color = 'text-red-500 font-bold';
                                } elseif ($lvl >= 10) {
                                    $label = 'high';
                                    $labelText = 'High';
                                    $color = 'text-red-400 font-semibold';
                                } elseif ($lvl >= 5) {
                                    $label = 'medium';
                                    $labelText = 'Medium';
                                    $color = 'text-yellow-400';
                                } else {
                                    $label = 'low';
                                    $labelText = 'Low';
                                    $color = 'text-green-400';
                                }

                                $alertDate = isset($alert['time']) ? date('Y-m-d', strtotime($alert['time'])) : '';
                            @endphp
                            <tr class="table-row-hover alert-row" data-severity="{{ $label }}" data-date="{{ $alertDate }}">
                                <td class="p-3 text-gray-400">
                                    {{ isset($alert['time']) ? date('H:i:s', strtotime($alert['time'])) : '00:00' }}
                                </td>
                                <td class="p-3">
                                    <span class="{{ $color }}">{{ $labelText }} ({{ $lvl }})</span>
                                </td>
                                <td class="p-3">{{ $alert['agent']['name'] ?? 'unknown' }}</td>
                                <td class="p-3 text-gray-300 font-mono">{{ $alert['user'] ?? 'unknown' }}</td>
                                <td class="p-3 max-w-xs truncate text-left search-target"
                                    title="{{ $alert['description'] }}">
                                    {{ $alert['description'] }}
                                </td>
                                <td class="p-3">
                                    <span class="{{ $lvl >= 10 ? 'text-red-400' : 'text-green-400' }}">
                                        {{ $lvl >= 10 ? 'Active' : 'Resolved' }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <button onclick="openModal({{ json_encode($alert) }})"
                                        class="text-cyan-400 hover:text-cyan-300 hover:underline transition">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-500 bg-[#2B2D32]/10">
                                    No alerts logs recorded today.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="emptyFilterRow" class="hidden">
                            <td colspan="7" class="p-8 text-center text-gray-500 bg-[#2B2D32]/10">
                                No data matches the selected filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-4 flex items-center justify-between text-xs text-gray-400 border-t border-white">
                <div class="flex items-center gap-2">
                    Rows per page:
                    <select class="bg-transparent border border-white rounded px-1">
                        <option>10</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button class="hover:text-white"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                    <span class="text-white">1</span>
                    <button class="hover:text-white"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                </div>
            </div>
        </div>
    </main>

    <div id="alertModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div
            class="bg-[#2B2D32] border border-gray-700 rounded-2xl w-full max-w-lg max-h-[650px] flex flex-col animate-fade-in">

            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700 shrink-0">
                <div class="flex items-center gap-3">
                    <div id="modalIconContainer" class="w-10 h-10 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 id="modalTitle" class="text-sm font-semibold text-white truncate max-w-[320px]">Alert Detail
                        </h2>
                        <p class="text-[11px] text-gray-400">Security Event Detail</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-5 space-y-4 overflow-y-auto flex-grow [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <div class="flex items-center gap-2 flex-wrap">
                    <span id="modalSeverity" class="px-2 py-0.5 rounded-full text-[10px] font-bold">LEVEL</span>
                    <span id="modalStatus" class="px-2 py-0.5 rounded-full text-[10px]">Active</span>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between border-b border-gray-700/50 pb-1.5">
                        <span class="text-gray-500">Timestamp Log</span>
                        <span id="modalTime" class="text-white font-mono">-</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-700/50 pb-1.5">
                        <span class="text-gray-500">Target Agent</span>
                        <span id="modalAgent" class="text-white">-</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-700/50 pb-1.5">
                        <span class="text-gray-500">Agent ID</span>
                        <span id="modalAgentId" class="text-gray-300 font-mono">-</span>
                    </div>
                    <div class="flex justify-between pb-1.5">
                        <span class="text-gray-500">Affected User</span>
                        <span id="modalUser" class="text-yellow-400 font-mono">-</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-semibold text-cyan-400 mb-1.5">Description</h3>
                    <div class="bg-[#111827] border border-gray-700 rounded-lg p-3">
                        <p id="modalDesc" class="text-xs text-gray-300 leading-relaxed">-</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-semibold text-cyan-400 mb-1.5">Mapped Alert Object (JSON)</h3>
                    <div class="bg-black border border-gray-700 rounded-lg p-3 max-h-[180px] overflow-auto">
                        <pre id="modalRaw" class="text-green-400 text-[10px] font-mono leading-tight">-</pre>
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 border-t border-gray-700 flex items-center justify-end shrink-0">
                <button onclick="closeModal()"
                    class="px-3 py-1.5 rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700 transition text-xs">
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
        const colorSet = hasData ? ['#ef4444', '#f87171', '#facc15', '#4ade80'] : ['#4a4e54'];

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