<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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

@include('Admin.components.header-admin')

<div class="bg-surface px-6 flex items-center justify-between border-b border-borderSubtle animate-fade-in delay-1 mt-16 md:mt-0">
    <div class="flex gap-8">
        <a href="#" class="py-3 text-brand border-b-2 border-brand font-medium text-sm">Dashboard Analytics</a>
    </div>
</div>
<div class="p-6 max-w-[1400px] mx-auto">

    @if($wazuhOffline)
    <div class="mb-4 bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg shadow-[0_0_15px_rgba(239,68,68,0.1)]">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856 c1.54 0 2.502-1.667 1.732-3L13.732 4 c-.77-1.333-2.694-1.333-3.464 0L3.34 16 c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-semibold text-sm">Server Monitoring Offline</p>
                <p class="text-xs text-red-300">Data monitoring sementara tidak tersedia</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Statistik Atas --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 text-center">
        <div class="bg-surface border border-borderSubtle p-4 rounded-xl group cursor-default animate-fade-in delay-1 hover:border-green-500/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-green-400 transition-colors">Agent Active</p>
            <h2 class="text-3xl font-bold mt-2 text-green-400 drop-shadow-[0_0_10px_rgba(34,197,94,0.4)]">{{ $active }}</h2>
        </div>

        <div class="bg-surface border border-borderSubtle p-4 rounded-xl group cursor-default animate-fade-in delay-1 hover:border-yellow-500/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-yellow-400 transition-colors">Agent Pending</p>
            <h2 class="text-3xl font-bold mt-2 text-yellow-400 drop-shadow-[0_0_10px_rgba(234,179,8,0.4)]">{{ $pending }}</h2>
        </div>

        <div class="bg-surface border border-borderSubtle p-4 rounded-xl group cursor-default animate-fade-in delay-2 hover:border-red-500/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-red-400 transition-colors">Disconnected</p>
            <h2 class="text-3xl font-bold mt-2 text-red-400 drop-shadow-[0_0_10px_rgba(239,68,68,0.4)]">{{ $disconnected }}</h2>
        </div>

        <div class="bg-surface border border-borderSubtle p-4 rounded-xl group cursor-default animate-fade-in delay-2 hover:border-gray-500/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-textMain transition-colors">Never Connected</p>
            <h2 class="text-3xl font-bold mt-2 text-textMuted">{{ $never }}</h2>
        </div>
    </div>

    {{-- Baris Tengah: Status, Overview, Live Chart --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="border border-borderSubtle bg-surface p-5 rounded-xl hover-scale animate-fade-in delay-1">
            <h3 class="text-sm font-semibold mb-4 text-textMain">Agent by status</h3>
            <div class="grid grid-cols-2 grid-rows-2 h-40 border border-borderSubtle rounded-lg overflow-hidden">
                <div class="border-r border-b border-borderSubtle flex flex-col items-center justify-center p-2 hover:bg-green-500/10 transition-all">
                    <i data-lucide="user-check" class="w-5 h-5 text-green-400 mb-1"></i>
                    <span class="text-[10px] text-textMuted uppercase">Active</span>
                    <span class="font-bold text-green-400">{{ $active }}</span>
                </div>
                <div class="border-b border-borderSubtle flex flex-col items-center justify-center p-2 hover:bg-yellow-500/10 transition-all">
                    <i data-lucide="user-minus" class="w-5 h-5 text-yellow-400 mb-1"></i>
                    <span class="text-[10px] text-textMuted uppercase">Pending</span>
                    <span class="font-bold text-yellow-400">{{ $pending }}</span>
                </div>
                <div class="border-r border-borderSubtle flex flex-col items-center justify-center p-2 hover:bg-red-500/10 transition-all">
                    <i data-lucide="user-x" class="w-5 h-5 text-red-400 mb-1"></i>
                    <span class="text-[10px] text-textMuted uppercase">Disconnected</span>
                    <span class="font-bold text-red-400">{{ $disconnected }}</span>
                </div>
                <div class="flex flex-col items-center justify-center p-2 hover:bg-white/5 transition-all">
                    <i data-lucide="user" class="w-5 h-5 text-textMuted mb-1"></i>
                    <span class="text-[10px] text-textMuted uppercase">Never</span>
                    <span class="font-bold text-textMuted">{{ $never }}</span>
                </div>
            </div>
        </div>

        <div class="border border-borderSubtle bg-surface p-5 rounded-xl flex flex-col items-center justify-center relative hover-scale animate-fade-in delay-2 overflow-hidden">
            <h3 class="text-sm font-semibold absolute top-5 left-5 text-textMain">Overview Agent</h3>
            @php
                $totalAgent = $active + $pending + $disconnected + $never;
                $activePercent = $totalAgent > 0 ? ($active / $totalAgent) * 100 : 0;
            @endphp
            <div class="w-32 h-32 rounded-full relative flex items-center justify-center drop-shadow-[0_0_15px_rgba(34,197,94,0.2)]"
                style="background: conic-gradient(#22c55e 0% {{ $activePercent }}%, #262833 {{ $activePercent }}% 100%);">
                <div class="w-20 h-20 bg-surface rounded-full"></div>
            </div>

            @if($totalAgent > 0)
                <p class="mt-5 text-xs font-bold tracking-widest text-green-400 animate-pulse">{{ $totalAgent }} AGENT DETECTED</p>
            @else
                <p class="mt-5 text-xs font-bold tracking-widest text-textMuted animate-pulse">NO AGENT DETECTED</p>
            @endif
        </div>

        <div class="border border-borderSubtle bg-surface p-5 rounded-xl hover-scale animate-fade-in delay-3">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-textMain">Threat Trend (7 Days)</h3>
                    <p class="text-xs text-textMuted mt-1">Total Alerts: <span class="text-red-400 font-bold">{{ $totalAlerts }}</span></p>
                </div>
                <span class="text-[10px] bg-brand/10 text-brand border border-brand/20 px-2 py-0.5 rounded font-medium pulse-soft">Live Analytics</span>
            </div>
            <div class="h-40">
                <canvas id="alertChart"></canvas>
            </div>
        </div>

    </div>

    <div class="border border-borderSubtle bg-surface rounded-xl overflow-hidden shadow-2xl animate-fade-in delay-3">
        <div class="p-4 flex items-center justify-between border-b border-borderSubtle bg-surface/50">
            <h3 class="font-semibold text-sm flex items-center gap-2 text-textMain">
                Registered Agents 
                <span id="agent-count" class="text-[10px] bg-page border border-borderSubtle px-2 py-0.5 rounded-full text-textMuted">{{ count($agents) }}</span>
            </h3>
            <button onclick="window.location.reload()"
                    class="flex items-center gap-2 px-3 py-1.5 bg-page hover:bg-borderSubtle border border-borderSubtle rounded-lg text-xs text-textMain transition duration-200">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5 group-hover:rotate-180 transition duration-500"></i> Refresh
            </button> 
        </div>

        <div class="p-3 bg-page border-b border-borderSubtle">
            <div class="bg-[#0d0e12] border border-borderSubtle rounded px-3 py-2 text-xs text-brand font-mono flex items-center gap-2">
                <span class="opacity-70">root@ccso-server:~# system_check --status</span>
                <div class="w-[6px] h-3 bg-brand animate-pulse"></div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-surface text-textMuted text-xs uppercase tracking-wider border-b border-borderSubtle">
                    <tr>
                        <th class="p-4 w-10"><input type="checkbox" class="accent-brand rounded bg-page border-borderSubtle"></th>
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">IP address</th>
                        <th class="p-4 font-semibold">Operating system</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-borderSubtle bg-page/30">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-surface/60 transition-colors">
                        <td class="p-4"><input type="checkbox" class="accent-brand rounded"></td>
                        <td class="p-4 text-textMuted font-mono text-xs">{{ $agent['id'] }}</td>
                        <td class="p-4 font-semibold text-textMain">{{ $agent['name'] }}</td>
                        <td class="p-4 text-textMuted font-mono text-xs">{{ $agent['ip'] }}</td>
                        <td class="p-4 text-textMuted text-xs">{{ $agent['os']['name'] ?? '-' }}</td>
                        <td class="p-4">
                            @if($agent['status'] == 'active')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
                            @elseif($agent['status'] == 'pending')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Pending</span>
                            @elseif($agent['status'] == 'disconnected')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Disconnected</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-500/10 text-gray-400 border border-gray-500/20">{{ $agent['status'] }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <button class="text-brand hover:text-brandHover text-xs font-medium hover:underline">View</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-8 text-textMuted text-sm italic">No Agent Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 flex items-center justify-between text-xs text-textMuted border-t border-borderSubtle bg-surface/50">
            <span>Showing {{ count($agents) }} of {{ count($agents) }} entries</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    lucide.createIcons();
    
    const ctx = document.getElementById('alertChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Alerts',
                    data: @json($chartData),
                    borderColor: '#6366f1', // Brand color
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#787f99', font: {size: 10} }, grid: { color: 'rgba(38, 40, 51, 0.5)' } },
                    y: { beginAtZero: true, ticks: { color: '#787f99', font: {size: 10} }, grid: { color: 'rgba(38, 40, 51, 0.5)' } }
                }
            }
        });
    }
</script>
</body>
</html>