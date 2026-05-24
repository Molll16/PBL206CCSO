<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #2b2d34;
            color: #e5e7eb;
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

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('daftarlog') }}"
                class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">View Logs</a>
        </div>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                
                    <!-- CRITICAL -->
                    <div class="bg-[#2B2D32] border border-red-700 rounded-xl p-5">
                        <p class="text-sm text-red-400 font-medium">
                            Critical
                        </p>
                
                        <h2 class="text-3xl font-bold text-red-500 mt-2">
                        {{ $criticalAlerts ?? 0 }}
                        </h2>
                    </div>
                
                    <!-- HIGH -->
                    <div class="bg-[#2B2D32] border border-red-500 rounded-xl p-5">
                        <p class="text-sm text-red-300 font-medium">
                            High
                        </p>
                
                        <h2 class="text-3xl font-bold text-red-400 mt-2">
                        {{ $highAlerts ?? 0 }}
                        </h2>
                    </div>
                
                    <!-- MEDIUM -->
                    <div class="bg-[#2B2D32] border border-yellow-500 rounded-xl p-5">
                        <p class="text-sm text-yellow-300 font-medium">
                            Medium
                        </p>
                
                        <h2 class="text-3xl font-bold text-yellow-400 mt-2">
                            {{ $mediumAlerts ?? 0 }}
                        </h2>
                    </div>
                
                    <!-- LOW -->
                    <div class="bg-[#2B2D32] border border-green-500 rounded-xl p-5">
                        <p class="text-sm text-green-300 font-medium">
                            Low
                        </p>
                
                        <h2 class="text-3xl font-bold text-green-400 mt-2">
                            {{ $lowAlerts ?? 0 }}
                        </h2>
                    </div>
                
                </div>

        <div class="border border-white rounded-sm bg-transparent overflow-hidden">
            <div class="p-3 flex items-center justify-between border-b border-white">
                <div class="text-sm font-medium flex items-center gap-1">Alerts = {{ $totalAlerts ?? 0 }}</div>
            </div>

            <!-- FILTER & SEARCH -->
            <div class="p-4 border-b border-white flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
            
                <!-- LEFT -->
                <div class="flex flex-col md:flex-row gap-3">
            
                    <!-- FILTER SEVERITY -->
                    <select class="bg-[#2B2D32]
                               border border-gray-600
                               rounded-lg
                               px-3 py-2
                               text-sm text-white
                               focus:outline-none
                               focus:border-cyan-400">
            
                        <option>
                            All Severity
                        </option>
            
                        <option>
                            Critical
                        </option>
            
                        <option>
                            High
                        </option>
            
                        <option>
                            Medium
                        </option>
            
                        <option>
                            Low
                        </option>
            
                    </select>
            
                    <!-- FILTER DATE -->
                    <input type="date" class="bg-[#2B2D32]
                               border border-gray-600
                               rounded-lg
                               px-3 py-2
                               text-sm text-white
                               focus:outline-none
                               focus:border-cyan-400">
            
                </div>
            
                <!-- RIGHT -->
                <div class="flex gap-3">
            
                    <!-- SEARCH -->
                    <input type="text" placeholder="Search alerts..." class="bg-[#2B2D32]
                               border border-gray-600
                               rounded-lg
                               px-3 py-2
                               text-sm text-white
                               placeholder-gray-400
                               focus:outline-none
                               focus:border-cyan-400">
            
                    <!-- REFRESH -->
                    <button onclick="location.reload()" class="bg-cyan-500
                               hover:bg-cyan-600
                               transition
                               px-4 py-2
                               rounded-lg
                               text-sm
                               font-medium
                               text-white
                               flex items-center gap-2">
            
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            
                        Refresh
            
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
                            <th class="p-3 font-semibold">Event Type</th>
                            <th class="p-3 font-semibold">Description</th>
                            <th class="p-3 font-semibold">Source IP</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50 text-center">
                        @forelse($alerts as $alert)

                            <tr class="table-row-hover">
                                <td class="p-3 text-gray-400">
                                    {{ \Carbon\Carbon::parse($alert['time'])->format('H:i') }}
                                </td>
                                <td class="p-3">
                                    @if($alert['level'] >= 13)
                                        <span class="text-red-500 font-bold">
                                            Critical
                                        </span>
                                    @elseif($alert['level'] >= 10)
                                        <span class="text-red-400 font-bold">
                                            High
                                        </span>
                                    @elseif($alert['level'] >= 5)
                                        <span class="text-yellow-400 font-bold">
                                            Medium
                                        </span>
                                    @else
                                        <span class="text-green-400 font-bold">
                                            Low
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    {{ $alert['agent']['name'] }}
                                </td>
                                <td class="p-3">
                                    Security Event
                                </td>
                                <td class="p-3 max-w-xs truncate">
                                    {{ $alert['description'] }}
                                </td>
                                <td class="p-3">
                                    {{ $alert['user'] }}
                                </td>
                                <td class="p-3">
                                    <span class="text-red-400">
                                        Active
                                    </span>
                                </td>
                                <td class="p-3">
                                    <button class="text-white hover:underline">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-gray-500">
                                    No alerts today
                                </td>
                            </tr>
                        @endforelse
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
                    <span class="hover:text-white cursor-pointer">2</span>
                    <button class="hover:text-white"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Inisialisasi icon lucide
        lucide.createIcons();
    </script>
</body>

</html>