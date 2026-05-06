<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/js/app.js')
</head>

<body class="bg-[#2B2D34] text-white min-h-screen">

@include('Admin.components.header-admin')

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('agents-list') }}" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Agents List</a>
            <a href="{{ route('assignagent') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Assign Agent</a>
        </div>
    </div>

    <div class="p-6">
        
        <div class="grid grid-cols-3 gap-4 mb-14 text-center">
          <div>
              <p class="text-lg text-gray-300">Total User</p>
              <h2 class="text-4xl font-bold mt-2">
                  {{ $totalUsers }}
              </h2>
          </div>

          <div>
              <p class="text-lg text-gray-300">Total Agent</p>
              <h2 class="text-4xl font-bold mt-2">
                  {{ $totalAgents }}
              </h2>
          </div>

          <div>
              <p class="text-lg text-gray-300">Agent Assigned</p>
              <h2 class="text-4xl font-bold mt-2">
                  {{ $totalAssignedAgents }}
              </h2>
          </div>
        </div>

    <h1 class="text-2xl font-bold mb-6">Agent List</h1>

    <div class="border border-gray-600 rounded-sm bg-transparent overflow-hidden">
            <div class="p-3 flex items-center justify-between border-b border-gray-600">
                <div class="text-sm font-medium">Agents</div>
                <div class="flex items-center gap-5 text-xs">
                    <button onclick="window.location.reload()"
                            class="mr-auto flex items-center gap-2 px-4 py-2 
                                   bg-[#1f2937] hover:bg-cyan-500 
                                   border border-gray-600 hover:border-cyan-400
                                   rounded-lg text-sm text-white 
                                   transition duration-200 shadow-sm">
                    
                        <i data-lucide="refresh-cw"
                           class="w-4 h-4 group-hover:rotate-180 transition duration-500">
                        </i>
                    
                        Refresh
                    </button> 
                </div>
            </div>

        <div class="border border-white/20 rounded-xl overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-black/20">
                    <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">IP</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">OS</th>
                    <th class="p-3 text-left">Added On</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($agents as $agent)
                        <tr class="border-t border-white/10 hover:bg-white/5">
                            <td class="p-3">{{ $agent['id'] }}</td>
                            <td class="p-3">{{ $agent['name'] }}</td>
                            <td class="p-3">{{ $agent['ip'] ?? '-' }}</td>

                            <td class="p-3">
                                @if($agent['status'] == 'active')
                            <span class="text-green-400">● Active</span>
                                @else
                            <span class="text-red-400">● Offline</span>
                                @endif
                            </td>

                            <td class="p-3">{{ $agent['os']['name'] ?? '-' }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($agent['dateAdd'])->format('d M Y H:i') }}</td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400">No Agent Found</td>
                        </tr>

                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

    <script>
    // Inisialisasi icon lucide
    lucide.createIcons();
    </script>
</body>
</html>