<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Real-time Dashboard - {{ $agentName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700&display=swap" rel="stylesheet">

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

    {{-- Breadcrumb / Navigation Header --}}
    <div class="bg-surface px-6 flex items-center justify-between border-b border-borderSubtle">
        <div class="flex gap-4 items-center py-3">
            <a href="{{ route('agents-list') }}"
                class="text-textMuted hover:text-textMain text-sm transition-colors flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Agen
            </a>
            <span class="text-borderSubtle">/</span>
            <span class="text-brand font-medium text-sm">Monitoring Real-time: {{ $agentName }}</span>
        </div>
    </div>

    <div class="p-6 mx-auto max-w-[1600px] space-y-6">

        {{-- BARIS 1: Metadata Info Utama Agen --}}
        <div
            class="bg-surface border border-borderSubtle rounded-xl p-6 flex flex-col md:flex-row justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-page border border-borderSubtle rounded-lg text-brand mt-1">
                    <i data-lucide="monitor" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl font-bold tracking-tight text-textMain">{{ $agentName }}</h1>
                        <span
                            class="font-mono text-xs bg-page border border-borderSubtle px-2 py-0.5 rounded text-textMuted">ID:
                            {{ $agentId }}</span>

                        @if($agentStatus == 'active')
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Active
                            </span>
                        @else
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Offline
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-1 mt-3 text-xs text-textMuted">
                        <p><span class="text-textMuted">IP Address:</span> <span
                                class="font-mono text-textMain">{{ $agentIp }}</span></p>
                        <p><span class="text-textMuted">OS:</span> <span class="text-textMain">{{ $agentOs }}</span></p>
                        <p><span class="text-textMuted">Last Keep-Alive:</span> <span
                                class="text-textMain">{{ $agentLastKeepAlive }}</span></p>
                        <p><span class="text-textMuted">Failed Login Logs:</span> <span
                                class="px-1.5 py-0.5 bg-red-500/10 text-red-400 rounded font-bold font-mono">{{ $failedLogins['count'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BARIS 2: Sistem Resources (CPU, RAM) & Integrasi Traffic Jaringan Asli --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Card Resource Perangkat Keras Pemakaian Asli --}}
            <div class="border border-borderSubtle bg-surface p-5 rounded-xl space-y-6">
                <h3
                    class="text-sm font-semibold text-textMain flex items-center gap-1.5 border-b border-borderSubtle pb-3">
                    <i data-lucide="cpu" class="w-4 h-4 text-brand"></i> Hardware Resource Utilization
                </h3>

                {{-- Progres Pemakaian CPU Asli --}}
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-textMuted font-medium">CPU Usage</span>
                        <span class="font-bold text-brand font-mono">{{ $hardware['cpu'] }}%</span>
                    </div>
                    <div class="w-full bg-page rounded-full h-2 border border-borderSubtle">
                        <div class="bg-brand h-2 rounded-full transition-all duration-500"
                            style="width: {{ $hardware['cpu'] }}%"></div>
                    </div>
                </div>

                {{-- Progres Pemakaian RAM Asli --}}
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-textMuted font-medium">RAM Usage</span>
                        <span class="font-bold text-emerald-400 font-mono">{{ $hardware['ram'] }}%</span>
                    </div>
                    <div class="w-full bg-page rounded-full h-2 border border-borderSubtle">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500"
                            style="width: {{ $hardware['ram'] }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Card Volume Bandwidth Jaringan Asli --}}
            <div class="border border-borderSubtle bg-surface p-5 rounded-xl space-y-4">
                <h3
                    class="text-sm font-semibold text-textMain flex items-center gap-1.5 border-b border-borderSubtle pb-3">
                    <i data-lucide="activity" class="w-4 h-4 text-cyan-400"></i> Network Traffic Volume
                </h3>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-page border border-borderSubtle rounded-lg p-3">
                        <div class="flex items-center justify-center gap-1 text-xs text-textMuted mb-1">
                            <i data-lucide="arrow-down-left" class="w-3.5 h-3.5 text-blue-400"></i> Inbound
                        </div>
                        <span class="text-xl font-bold font-mono text-textMain">{{ $network['stats']['inbound'] }} <span
                                class="text-xs text-textMuted">GB</span></span>
                    </div>
                    <div class="bg-page border border-borderSubtle rounded-lg p-3">
                        <div class="flex items-center justify-center gap-1 text-xs text-textMuted mb-1">
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-purple-400"></i> Outbound
                        </div>
                        <span class="text-xl font-bold font-mono text-textMain">{{ $network['stats']['outbound'] }}
                            <span class="text-xs text-textMuted">GB</span></span>
                    </div>
                </div>
                <div class="text-[11px] text-textMuted text-center bg-page/40 p-2 rounded border border-borderSubtle">
                    Status Tag Jaringan: <span class="font-bold text-brand">{{ $failedLogins['status_tag'] }}</span>
                </div>
            </div>

            {{-- Card File Integrity Monitoring (FIM) Real-time --}}
            <div class="border border-borderSubtle bg-surface p-5 rounded-xl space-y-4">
                <h3
                    class="text-sm font-semibold text-textMain flex items-center gap-1.5 border-b border-borderSubtle pb-3">
                    <i data-lucide="folder-sync" class="w-4 h-4 text-yellow-400"></i> File Integrity Audit (FIM)
                </h3>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-page border border-borderSubtle rounded-lg p-2">
                        <span class="text-[10px] font-bold text-textMuted uppercase">Added</span>
                        <span class="block text-lg font-bold text-textMain font-mono mt-1">{{ $fimAdded }}</span>
                    </div>
                    <div class="bg-page border border-borderSubtle rounded-lg p-2">
                        <span class="text-[10px] font-bold text-textMuted uppercase">Modified</span>
                        <span class="block text-lg font-bold text-yellow-400 font-mono mt-1">{{ $fimModified }}</span>
                    </div>
                    <div class="bg-page border border-borderSubtle rounded-lg p-2">
                        <span class="text-[10px] font-bold text-textMuted uppercase">Deleted</span>
                        <span class="block text-lg font-bold text-red-400 font-mono mt-1">{{ $fimDeleted }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- BARIS 3: Split Konten List Tabel Data Asli dari OS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Tabel Pemantauan Status Service Sistem (Nginx, MySQL, dll) --}}
            <div class="border border-borderSubtle bg-surface rounded-xl overflow-hidden shadow-md flex flex-col">
                <div class="p-4 border-b border-borderSubtle bg-surface/50">
                    <h3 class="font-semibold text-sm flex items-center gap-2 text-textMain">
                        <i data-lucide="server" class="w-4 h-4 text-brand"></i> Monitored Core Services Status
                    </h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-page/50 text-textMuted uppercase font-semibold border-b border-borderSubtle">
                            <tr>
                                <th class="p-3">Service Name</th>
                                <th class="p-3 text-center">Port</th>
                                <th class="p-3 text-right">Status Operational</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-borderSubtle bg-page/10">
                            @forelse($services as $svc)
                                <tr class="hover:bg-page/30 transition-colors">
                                    <td class="p-3 font-semibold text-textMain">{{ $svc['name'] }}</td>
                                    <td class="p-3 text-center font-mono text-textMuted">{{ $svc['port'] }}</td>
                                    <td class="p-3 text-right">
                                        @if($svc['status'] === 'running')
                                            <span
                                                class="px-2 py-0.5 bg-green-500/10 text-green-400 rounded-md font-bold text-[10px] border border-green-500/20">RUNNING</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 bg-red-500/10 text-red-400 rounded-md font-bold text-[10px] border border-red-500/20">STOPPED</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center p-6 text-textMuted italic">No monitoring services
                                        active on target OS</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Riwayat Aktivitas Log Sesi Masuk User --}}
            <div class="border border-borderSubtle bg-surface rounded-xl overflow-hidden shadow-md flex flex-col">
                <div class="p-4 border-b border-borderSubtle bg-surface/50">
                    <h3 class="font-semibold text-sm flex items-center gap-2 text-textMain">
                        <i data-lucide="user-check" class="w-4 h-4 text-emerald-400"></i> Recent User Login Sesi
                        Activities
                    </h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-page/50 text-textMuted uppercase font-semibold border-b border-borderSubtle">
                            <tr>
                                <th class="p-3">Account User</th>
                                <th class="p-3">Activity description</th>
                                <th class="p-3 text-right">Timestamp Log</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-borderSubtle bg-page/10">
                            @forelse($loginActivities as $log)
                                <tr class="hover:bg-page/30 transition-colors">
                                    <td class="p-3 font-mono text-brand font-semibold">{{ $log['user'] }}</td>
                                    <td class="p-3 text-textMain font-medium">{{ $log['activity'] }}</td>
                                    <td class="p-3 text-right text-textMuted font-mono">{{ $log['time'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center p-6 text-textMuted italic">No core user session logs
                                        captured recently</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- BARIS 4: Detail Rincian Network Interface (Kartu Jaringan Aktif) --}}
        <div class="border border-borderSubtle bg-surface rounded-xl overflow-hidden shadow-md">
            <div class="p-4 border-b border-borderSubtle bg-surface/50">
                <h3 class="font-semibold text-sm flex items-center gap-2 text-textMain">
                    <i data-lucide="network" class="w-4 h-4 text-cyan-400"></i> Active Network Interface Adapters
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-page/50 text-textMuted uppercase font-semibold border-b border-borderSubtle">
                        <tr>
                            <th class="p-3">Interface Name</th>
                            <th class="p-3">IP / Mac Address Binding</th>
                            <th class="p-3 text-center">Adapter State</th>
                            <th class="p-3 text-right">RX Speed/Size</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-borderSubtle bg-page/10">
                        @forelse($network['interfaces'] as $iface)
                            <tr class="hover:bg-page/30 transition-colors">
                                <td class="p-3 font-semibold text-textMain">{{ $iface['name'] }}</td>
                                <td class="p-3 font-mono text-textMuted">{{ $iface['ip'] }}</td>
                                <td class="p-3 text-center">
                                    @if($iface['direction'] === 'up')
                                        <span
                                            class="px-1.5 py-0.5 bg-green-500/10 text-green-400 rounded font-bold text-[10px]">UP</span>
                                    @else
                                        <span
                                            class="px-1.5 py-0.5 bg-red-500/10 text-red-400 rounded font-bold text-[10px]">DOWN</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right font-mono text-textMain font-bold">{{ $iface['speed'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-6 text-textMuted italic">No hardware networks card
                                    detected</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // Nyalakan grafis ikon Lucide otomatis
        lucide.createIcons();
    </script>
</body>

</html>