<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin CCSO</title>
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

    <div class="p-6 max-w-[1400px] mx-auto">

        <main class="p-8 max-w-7xl mx-auto space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-textMain">Agen Terdaftar (Wazuh)</h2>
                    <p class="text-sm text-textMuted mt-1">Daftar entitas perangkat server dan workstation yang
                        terhubung.</p>
                </div>
                <a href="{{ route('assignagent') }}">
                    <button
                        class="bg-brand hover:bg-brandHover text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-brand/20 transition-all duration-200">
                        ⚙️ Tugaskan Agen
                    </button>
                </a>
            </div>

            {{-- Table --}}
            <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr class="border-b border-borderSubtle bg-page/30 text-[11px] font-bold uppercase tracking-wider text-textMuted">
                                <th class="py-3 px-5">ID</th>
                                <th class="py-3 px-5">Name</th>
                                <th class="py-3 px-5">IP</th>
                                <th class="py-3 px-5">Status</th>
                                <th class="py-3 px-5">OS</th>
                                <th class="py-3 px-5">Added On</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-borderSubtle text-xs text-textMain">
                            @forelse($agents as $agent)
                            <tr class="hover:bg-page/40 transition-colors">
                                <td class="py-3.5 px-5 text-textMuted font-mono">{{ $agent['id'] }}</td>
                                <td class="py-3.5 px-5 font-semibold text-brand">{{ $agent['name'] }}</td>
                                <td class="py-3.5 px-5 text-textMuted font-mono">{{ $agent['ip'] ?? '-' }}</td>

                                <td class="py-3.5 px-5">
                                    @if($agent['status'] == 'active')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">● Active</span>
                                    @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">● Offline</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-5 text-textMuted">{{ $agent['os']['name'] ?? '-' }}</td>
                                <td class="py-3.5 px-5 text-textMuted">{{ \Carbon\Carbon::parse($agent['dateAdd'])->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-textMuted">No Agent Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>