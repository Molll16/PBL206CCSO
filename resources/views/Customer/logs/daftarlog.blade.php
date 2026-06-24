<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { page: '#121318', surface: '#1a1b23', borderSubtle: '#262833', textMain: '#f1f3f9', textMuted: '#787f99', brand: '#6366f1' }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-page text-textMain font-sans antialiased">

    @include('Customer.components.header')

    <div class="px-6 py-3 border-b border-borderSubtle flex gap-8">
        <a href="#" class="text-brand text-sm border-b-2 border-brand font-medium">View Logs</a>
    </div>

    <main class="p-6 mx-auto max-w-7xl">

        {{-- 1. BAGIAN ATAS: GRAFIK & KOTAK METRIK COUNTER --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <div
                class="lg:col-span-4 bg-surface border border-borderSubtle rounded-xl p-5 flex flex-col items-center justify-center min-h-[220px]">
                <p
                    class="text-xs font-semibold uppercase tracking-wider text-textMuted self-start mb-2 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-brand"></i> Alert Distribution
                </p>
                <div class="w-full max-w-[150px] relative flex items-center justify-center">
                    <canvas id="alertPieChart"></canvas>
                </div>
            </div>

            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-surface border border-red-900/50 rounded-xl p-5 flex flex-col justify-between">
                    <p class="text-xs font-semibold text-red-400 uppercase">Critical</p>
                    <h2 class="text-3xl font-bold text-red-500 mt-2">{{ $criticalAlerts ?? 0 }}</h2>
                </div>
                <div class="bg-surface border border-red-800/40 rounded-xl p-5 flex flex-col justify-between">
                    <p class="text-xs font-semibold text-red-300 uppercase">High</p>
                    <h2 class="text-3xl font-bold text-red-400 mt-2">{{ $highAlerts ?? 0 }}</h2>
                </div>
                <div class="bg-surface border border-yellow-700/40 rounded-xl p-5 flex flex-col justify-between">
                    <p class="text-xs font-semibold text-yellow-300 uppercase">Medium</p>
                    <h2 class="text-3xl font-bold text-yellow-400 mt-2">{{ $mediumAlerts ?? 0 }}</h2>
                </div>
                <div class="bg-surface border border-green-800/40 rounded-xl p-5 flex flex-col justify-between">
                    <p class="text-xs font-semibold text-green-300 uppercase">Low</p>
                    <h2 class="text-3xl font-bold text-green-400 mt-2">{{ $lowAlerts ?? 0 }}</h2>
                </div>
            </div>
        </div>

        {{-- FILTER BAR (Disesuaikan Presisi dengan UI Dashboard Custom kamu) --}}
        <form method="GET" action="{{ url()->current() }}"
            class="mb-5 flex flex-wrap items-end gap-4 bg-[#1a1b23] border border-[#262833] p-4 rounded-xl">
        
            {{-- Filter Dropdown Severity/Level --}}
            <div class="flex flex-col gap-1.5 flex-1 min-w-[160px] sm:flex-none">
                <label class="text-[11px] text-[#787f99] font-bold uppercase tracking-wider">Severity</label>
                <div class="relative">
                    <select name="severity" onchange="this.form.submit()"
                        class="w-full appearance-none bg-[#121318] border border-[#262833] rounded-lg px-3 py-2 text-xs text-[#f1f3f9] focus:outline-none focus:border-[#6366f1] cursor-pointer pr-8">
                        <option value="">All Levels</option>
                        <option value="critical" {{ ($selectedSeverity ?? '') == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="high" {{ ($selectedSeverity ?? '') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ ($selectedSeverity ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ ($selectedSeverity ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#787f99]">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>
        
            {{-- Filter Input Tanggal (History/Hari ini) --}}
            <div class="flex flex-col gap-1.5 flex-1 min-w-[160px] sm:flex-none">
                <label class="text-[11px] text-[#787f99] font-bold uppercase tracking-wider">Date</label>
                <input type="date" name="date" value="{{ $selectedDate ?? now()->format('Y-m-d') }}"
                    onchange="this.form.submit()"
                    class="w-full bg-[#121318] border border-[#262833] rounded-lg px-3 py-2 text-xs text-[#f1f3f9] focus:outline-none focus:border-[#6366f1] [color-scheme:dark] cursor-pointer">
            </div>
        
            {{-- Tombol Reset Filter jika sedang aktif --}}
            @if(request('severity') || request('date'))
                <div class="pb-0.5">
                    <a href="{{ url()->current() }}"
                        class="inline-flex items-center gap-1.5 text-xs text-[#787f99] hover:text-[#f1f3f9] border border-[#262833] bg-[#121318]/50 px-3 py-2 rounded-lg transition-all">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Clear Filter
                    </a>
                </div>
            @endif
        </form>

        {{-- 2. TABEL LOGS --}}
        <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-borderSubtle bg-page/30 flex justify-between items-center">
                <span class="text-xs font-bold uppercase text-textMuted">Alerts Logs:
                    {{ date('d M Y', strtotime($selectedDate)) }} (Total: {{ $alerts->total() }})</span>
                <button onclick="location.reload()"
                    class="bg-brand text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Refresh
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface text-textMuted uppercase font-semibold border-b border-borderSubtle">
                            <th class="p-4">Time</th>
                            <th class="p-4">Level</th>
                            <th class="p-4">Agent</th>
                            <th class="p-4">User</th>
                            <th class="p-4">Threat Description</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-borderSubtle bg-page/10">
                        @forelse($alerts as $alert)
                                            <tr class="hover:bg-surface/60 transition-colors">
                                                <td class="p-4 font-mono text-textMuted">
                                                    {{ isset($alert['time']) ? date('H:i:s', strtotime($alert['time'])) : '00:00' }}
                                                </td>
                                                <td class="p-4">
                                                    <span
                                                        class="px-2 py-0.5 rounded text-[10px] font-bold border 
                                                            {{ $alert['level'] >= 13 ? 'bg-red-500/10 text-red-500 border-red-500/20' :
        ($alert['level'] >= 10 ? 'bg-red-400/10 text-red-400 border-red-400/20' :
            ($alert['level'] >= 5 ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : 'bg-green-500/10 text-green-400 border-green-500/20')) }}">
                                                        LVL {{ $alert['level'] ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="p-4 font-semibold text-textMain">{{ $alert['agent']['name'] ?? 'unknown' }}</td>
                                                <td class="p-4 font-mono text-textMuted">{{ $alert['user'] ?? 'unknown' }}</td>
                                                <td class="p-4 text-textMain max-w-xs truncate" title="{{ $alert['description'] }}">
                                                    {{ $alert['description'] }}
                                                </td>
                                                <td class="p-4 text-right">
                                                    <button onclick="openModal({{ json_encode($alert) }})"
                                                        class="text-brand hover:underline font-semibold">
                                                        View Details
                                                    </button>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-8 text-textMuted italic">No log data available for
                                    the selected filter criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER PAGINATION SLIDE (Maksimal 20 Baris Data Otomatis Muncul Navigasi Halaman) --}}
            @if($alerts->hasPages())
                <div class="p-4 border-t border-borderSubtle bg-page/20 flex justify-between items-center text-xs">
                    <span class="text-textMuted">Showing data {{ $alerts->firstItem() }} - {{ $alerts->lastItem() }}
                        of {{ $alerts->total() }} logs</span>
                    <div class="flex gap-2">
                        @if($alerts->onFirstPage())
                            <span
                                class="px-3 py-1.5 rounded-lg bg-borderSubtle text-textMuted cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $alerts->previousPageUrl() }}"
                                class="px-3 py-1.5 rounded-lg bg-brand text-white hover:bg-opacity-90">Previous</a>
                        @endif

                        @if($alerts->hasMorePages())
                            <a href="{{ $alerts->nextPageUrl() }}"
                                class="px-3 py-1.5 rounded-lg bg-brand text-white hover:bg-opacity-90">Next</a>
                        @else
                            <span class="px-3 py-1.5 rounded-lg bg-borderSubtle text-textMuted cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>

    {{-- 3. POP-UP MODAL DETAIL --}}
    <div id="alertModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div
            class="bg-surface border border-borderSubtle rounded-2xl w-full max-w-lg overflow-hidden flex flex-col shadow-2xl">
            <div class="p-5 border-b border-borderSubtle flex justify-between items-center bg-page/40">
                <div>
                    <h2 class="text-sm font-bold text-textMain">Alert Details</h2>
                </div>
                <button onclick="closeModal()" class="text-textMuted hover:text-textMain"><i data-lucide="x"
                        class="w-4 h-4"></i></button>
            </div>
            <div class="p-5 space-y-4 max-h-[450px] overflow-y-auto">
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between border-b border-borderSubtle pb-2">
                        <span class="text-textMuted">Target Agent:</span>
                        <span id="modalAgent" class="text-textMain font-semibold">-</span>
                    </div>
                    <div class="flex justify-between border-b border-borderSubtle pb-2">
                        <span class="text-textMuted">Event Time:</span>
                        <span id="modalTime" class="text-textMain font-mono">-</span>
                    </div>
                    <div class="flex justify-between border-b border-borderSubtle pb-2">
                        <span class="text-textMuted">User Affected:</span>
                        <span id="modalUser" class="text-yellow-400 font-mono">-</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-[11px] font-bold text-brand uppercase mb-1">Threat Description</h3>
                    <div class="bg-page border border-borderSubtle rounded-lg p-3 text-xs text-textMuted leading-relaxed"
                        id="modalDesc">-</div>
                </div>
                <div>
                    <h3 class="text-[11px] font-bold text-brand uppercase mb-1">Raw Object (JSON)</h3>
                    <div class="bg-page border border-borderSubtle rounded-lg p-3 max-h-[150px] overflow-auto">
                        <pre id="modalRaw" class="text-green-400 text-[10px] font-mono leading-tight">-</pre>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-borderSubtle bg-page/20 flex justify-end">
                <button onclick="closeModal()"
                    class="bg-borderSubtle text-textMain text-xs px-4 py-2 rounded-xl hover:bg-opacity-80 transition">Close</button>
            </div>
        </div>
    </div>

    {{-- 4. LOGIKA JAVASCRIPT --}}
    <script>
        lucide.createIcons();

        function openModal(alert) {
            document.getElementById('modalAgent').innerText = alert.agent?.name ?? 'unknown';
            document.getElementById('modalTime').innerText = alert.time ?? '-';
            document.getElementById('modalUser').innerText = alert.user ?? 'unknown';
            document.getElementById('modalDesc').innerText = alert.description ?? '-';
            document.getElementById('modalRaw').innerText = JSON.stringify(alert, null, 4);

            const modal = document.getElementById('alertModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('alertModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // LOGIKA GRAFIK LINGKARAN (CHART.JS)
        const criticalCount = {{ $criticalAlerts ?? 0 }};
        const highCount = {{ $highAlerts ?? 0 }};
        const mediumCount = {{ $mediumAlerts ?? 0 }};
        const lowCount = {{ $lowAlerts ?? 0 }};

        const total = criticalCount + highCount + mediumCount + lowCount;
        const ctx = document.getElementById('alertPieChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: total > 0 ? [criticalCount, highCount, mediumCount, lowCount] : [1],
                    backgroundColor: total > 0 ? ['#ef4444', '#f87171', '#facc15', '#4ade80'] : ['#262833'],
                    borderWidth: 0
                }]
            },
            options: { cutout: '75%', plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: true }
        });
    </script>
</body>

</html>