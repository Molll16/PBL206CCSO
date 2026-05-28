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

    <style>
        body {
            background-color: #121318;
            color: #f1f3f9;
            font-family: 'Inter', sans-serif;
        }

        .bg-card {
            background-color: #1a1b23;
        }

        .border-custom {
            border-color: #262833;
        }

        .table-row-hover:hover {
            background-color: rgba(99, 102, 241, 0.04);
            transition: all 0.2s;
        }

        /* Tab nav bar */
        .nav-bar {
            background-color: #1a1b23;
            border-bottom: 1px solid #262833;
        }

        /* Table container */
        .table-container {
            background: #1a1b23;
            border: 1px solid #262833;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        /* Input / select */
        .filter-input {
            background: #121318;
            border: 1px solid #262833;
            color: #f1f3f9;
            border-radius: 0.5rem;
            transition: border-color 0.2s;
        }
        .filter-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.5);
        }

        /* Accent bar */
        .accent-bar {
            background: #6366f1;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        /* Modal */
        .modal-card {
            background: #1a1b23;
            border: 1px solid #262833;
        }

        .modal-code-block {
            background: #0d0e12;
            border: 1px solid #262833;
        }

        .modal-desc-block {
            background: #0d0e12;
            border: 1px solid #262833;
        }

        /* Divider */
        .row-divider {
            border-color: #262833;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        /* Scrollbar hide */
        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="min-h-screen">

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

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">

        {{-- Top Stats --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

            {{-- Pie Chart --}}
            <div class="lg:col-span-4 bg-card border border-borderSubtle rounded-xl p-5 flex flex-col items-center justify-center min-h-[260px]">
                <p class="text-sm font-medium text-textMuted self-start mb-2 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-brand"></i> Alert Distribution
                </p>
                <div class="w-full max-w-[180px] relative flex items-center justify-center">
                    <canvas id="alertPieChart"></canvas>
                </div>
            </div>

            {{-- Severity Cards --}}
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="bg-card border border-red-900/50 rounded-xl p-5 flex flex-col justify-between hover:border-red-700/60 transition">
                    <p class="text-sm text-red-400 font-medium flex items-center justify-between">
                        Critical <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    </p>
                    <h2 class="text-4xl font-bold text-red-500 mt-2">{{ $criticalAlerts ?? 0 }}</h2>
                </div>

                <div class="bg-card border border-red-800/40 rounded-xl p-5 flex flex-col justify-between hover:border-red-600/50 transition">
                    <p class="text-sm text-red-300 font-medium">High</p>
                    <h2 class="text-4xl font-bold text-red-400 mt-2">{{ $highAlerts ?? 0 }}</h2>
                </div>

                <div class="bg-card border border-yellow-700/40 rounded-xl p-5 flex flex-col justify-between hover:border-yellow-500/50 transition">
                    <p class="text-sm text-yellow-300 font-medium">Medium</p>
                    <h2 class="text-4xl font-bold text-yellow-400 mt-2">{{ $mediumAlerts ?? 0 }}</h2>
                </div>

                <div class="bg-card border border-green-800/40 rounded-xl p-5 flex flex-col justify-between hover:border-green-600/50 transition">
                    <p class="text-sm text-green-300 font-medium">Low</p>
                    <h2 class="text-4xl font-bold text-green-400 mt-2">{{ $lowAlerts ?? 0 }}</h2>
                </div>

            </div>
        </div>

        {{-- Table --}}
        <div class="table-container">

            {{-- Table Header --}}
            <div class="p-3 flex items-center justify-between border-b border-borderSubtle">
                <div class="text-sm font-medium text-textMuted flex items-center gap-1">
                    Alerts = {{ $alerts->count() }}
                </div>
            </div>

            {{-- Filters --}}
            <div class="p-4 border-b border-borderSubtle flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                <div class="flex flex-col md:flex-row gap-3">
                    <select id="filterSeverity" class="filter-input px-3 py-2 text-sm">
                        <option value="all">All Severity</option>
                        <option value="critical">Critical</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <input type="date" id="filterDate" class="filter-input px-3 py-2 text-sm">
                </div>

                <div class="flex gap-3">
                    <input type="text" id="searchAlert" placeholder="Search alerts..."
                        class="filter-input px-3 py-2 text-sm placeholder-textMuted">
                    <button onclick="clearFilters()"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-textMuted transition"
                        style="background: rgba(255,255,255,0.04); border: 1px solid #262833;"
                        onmouseover="this.style.color='#f1f3f9'"
                        onmouseout="this.style.color='#787f99'">
                        Reset
                    </button>
                    <button onclick="location.reload()"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white flex items-center gap-2 transition"
                        style="background: #6366f1; box-shadow: 0 0 14px rgba(99,102,241,0.3);"
                        onmouseover="this.style.background='#4f46e5'"
                        onmouseout="this.style.background='#6366f1'">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
                    </button>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-borderSubtle text-center text-textMuted text-[10px] tracking-wider">
                            <th class="p-3 font-semibold">Time</th>
                            <th class="p-3 font-semibold">Severity</th>
                            <th class="p-3 font-semibold">Agent</th>
                            <th class="p-3 font-semibold">Affected User</th>
                            <th class="p-3 font-semibold">Description</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="alertTableBody" class="divide-y divide-borderSubtle text-center">
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
                                <td class="p-3 text-textMuted">
                                    {{ isset($alert['time']) ? date('H:i:s', strtotime($alert['time'])) : '00:00' }}
                                </td>
                                <td class="p-3">
                                    <span class="{{ $color }}">{{ $labelText }} ({{ $lvl }})</span>
                                </td>
                                <td class="p-3 text-textMain">{{ $alert['agent']['name'] ?? 'unknown' }}</td>
                                <td class="p-3 text-textMuted font-mono">{{ $alert['user'] ?? 'unknown' }}</td>
                                <td class="p-3 max-w-xs truncate text-left search-target text-textMain"
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
                                        class="text-brand hover:text-brandHover hover:underline transition">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-textMuted">
                                    No alerts logs recorded today.
                                </td>
                            </tr>
                        @endforelse
                        <tr id="emptyFilterRow" class="hidden">
                            <td colspan="7" class="p-8 text-center text-textMuted">
                                No data matches the selected filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 flex items-center justify-between text-xs text-textMuted border-t border-borderSubtle">
                <div class="flex items-center gap-2">
                    Rows per page:
                    <select class="filter-input rounded px-2 py-0.5">
                        <option>10</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button class="hover:text-textMain transition"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                    <span class="text-textMain">1</span>
                    <button class="hover:text-textMain transition"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
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