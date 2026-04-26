<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Security Dashboard - Interactive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/js/app.js')
    <style>
        body { font-family: 'Inter', sans-serif; background-color: ##2b2d34; color: #e5e7eb; overflow-x: hidden; }
        .bg-card { background-color: #2b2d32; }
        .border-custom { border-color: #4a4e54; }
        .table-row-hover:hover { background-color: rgba(255, 255, 255, 0.05); transition: all 0.2s; }
        
        /* Animasi Baru */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        
        .cursor-blink { animation: blink 1s infinite; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

        /* Smooth Transition untuk Hover */
        .hover-scale { transition: transform 0.2s ease; }
        .hover-scale:hover { transform: scale(1.02); }
    </style>
    
</head>
<body class="min-h-screen bg-[#2B2D34]">


<!--sidebar totalitas-->

    <div id="sidebar-backdrop"
       onclick="closeSidebar()"
       class="fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity duration-300">
    </div>

  <!-- SIDEBAR -->
  <aside id="sidebar"
         class="fixed top-0 left-0 h-full w-52 bg-[#1a1a1a] border-r border-gray-700 z-40 flex flex-col -translate-x-full transition-transform duration-300 ease-in-out">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-3 border-b border-gray-700">
      <div class="flex items-center gap-4">
        <img src="/ob/logo.png" class="w-5">
        <span class="text-xs tracking-wide leading-tight">Central Cyber <br> Security Office</span>
      </div>
      <button onclick="closeSidebar()"
              class="w-7 h-7 flex items-center justify-center rounded hover:bg-gray-700 transition text-gray-400 hover:text-white text-base">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>

      </button>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-2 overflow-y-auto">

      <!-- Dashboard -->
      <a href={{ route('dashboard-admin') }}
         class="flex items-center gap-3 px-4 py-2.5 text-sm text-white hover:bg-[#2c2c2c] transition border-l-[3px] border-[#3282B8] bg-[#3282B8]/10">
        <img src="/db/layout.png" class="w-4" alt="">
        Dashboard
      </a>

      <!-- Customization -->
      <div>

        <button onclick="toggleSubmenu('customization-menu', 'chevron-customization')"
                class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-[#2c2c2c] transition border-l-[3px] border-transparent">
          <div class="flex items-center gap-3">
            <img src="/db/custom.png" class="w-4" alt="">
            Customization
          </div>
          <svg id="chevron-customization"
               class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
               fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <div id="customization-menu" class="hidden bg-[#161616]">
          <a href={{ route('adduser') }} class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Add User</a>
        </div>

      </div>

      <!-- Agent -->
      <div>
        <button onclick="toggleSubmenu('agent-menu', 'chevron-agent')"
                class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-[#2c2c2c] transition border-l-[3px] border-transparent">

          <div class="flex items-center gap-3">
            <img src="/db/agent.png" class="w-4" alt="">
            Agent
          </div>

          <svg id="chevron-agent"
               class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
               fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7"/>
          </svg>

        </button>

        <div id="agent-menu" class="hidden bg-[#161616]">
          <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Information</a>
          <a href={{ route('assignagent') }} class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Deployment</a>
        </div>
      </div>

    </nav>
  </aside>

<!-- end sidebar -->

<!-- navbar totalitas bagian atas pertama -->

  <!-- NAVBAR -->
  <header class="bg-[#212121] border-b-2 border-white sticky top-0 z-20">
    <div class="flex items-center h-16">

      <button onclick="openSidebar()"
              class="flex items-center justify-center w-16 h-16 border-r-4 border-white hover:bg-[#2c2c2c] transition">
        <div class="w-8 h-8 border-2 border-white rounded-lg flex items-center justify-center">
          <img src="/ob/sidebar.png" class="w-3 h-3" alt="">
        </div>
      </button>

      <button class="flex items-center justify-center w-16 h-16 hover:bg-[#2c2c2c] transition">
        <img src="/ob/home.png" class="w-8" alt="">
      </button>

      <div class="flex items-center gap-3 px-2">
        <img src="/ob/logo.png" class="w-6" alt="">
        <div>
          <p class="text-xs tracking-wide">Central Cyber</p>
          <p class="text-xs tracking-wide">Security Office</p>
        </div>
      </div>

      <div class="flex-1"></div>

      <div class="flex items-center gap-3 pr-3">

        <!-- Manage Dropdown -->
        <div class="relative">

          <button onclick="toggleManage()"
                  class="flex items-center gap-2 border border-white rounded-md px-4 py-2 text-sm hover:bg-[#2c2c2c] transition">
            Manage
            <img src="/db/arrow.png" id="arrow-manage" class="w-3 transition-transform duration-200" alt="">

            <svg id="chevron-manage"
               class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
               fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7"/>
          </svg>

          </button>

          <!-- Dropdown Menu -->
          <div id="manage-menu"
               class="hidden absolute right-0 mt-2 w-32 bg-[#1e1e1e] border rounded-md shadow-xl overflow-hidden">
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Profile Settings</a>
            <a href="{{ route('login') }}" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Logout</a>
          </div>

        </div>
      </div>
    </div>
  </header>

<!-- penutup navbar totalitas bagian atas pertama -->

<!-- navbar kedua -->
    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="#" class="py-3 text-cyan-400 border-b-2 border-blue-400 font-medium text-sm">Dashboard</a>
            <a href="#" class="py-3 text-white/60 hover:text-white transition text-sm">Events</a>
        </div>
        <a href="#" class="text-white/60 hover:text-white transition text-sm">Profile Settings</a>
    </div>
<!-- penutup navbar kedua -->


    <div class="p-6 max-w-[1400px] mx-auto">

    {{-- Statistik Atas --}}
    <div class="grid grid-cols-4 gap-4 mb-8 text-center">

        <div class="group cursor-default animate-fade-in delay-1">
            <p class="text-sm text-white group-hover:text-blue-400 transition-colors">
                All Agent Active
            </p>
            <h2 class="text-4xl font-bold mt-1 text-green-400">
                {{ $active }}
            </h2>
        </div>

        <div class="group cursor-default animate-fade-in delay-1">
            <p class="text-sm text-white group-hover:text-yellow-400 transition-colors">
                All Agent Pending
            </p>
            <h2 class="text-4xl font-bold mt-1 text-yellow-400">
                {{ $pending }}
            </h2>
        </div>

        <div class="group cursor-default animate-fade-in delay-2">
            <p class="text-sm text-white group-hover:text-red-400 transition-colors">
                All Agent Disconnected
            </p>
            <h2 class="text-4xl font-bold mt-1 text-red-400">
                {{ $disconnected }}
            </h2>
        </div>

        <div class="group cursor-default animate-fade-in delay-2">
            <p class="text-sm text-white">
                All Agent Never Connected
            </p>
            <h2 class="text-4xl font-bold mt-1 text-gray-400">
                {{ $never }}
            </h2>
        </div>

    </div>

    {{-- Agent By Status --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="border border-white p-4 rounded bg-transparent hover-scale animate-fade-in delay-1">
            <h3 class="text-sm mb-4 text-white">Agent by status</h3>

            <div class="grid grid-cols-2 grid-rows-2 h-40 border-l border-t border-custom">

                <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-green-500/10 transition-all">
                    <i data-lucide="user-check" class="w-5 h-5 text-green-400 mb-1"></i>
                    <span class="text-[10px] text-gray-400 uppercase">Active</span>
                    <span class="font-bold text-green-400">{{ $active }}</span>
                </div>

                <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-yellow-500/10 transition-all">
                    <i data-lucide="user-minus" class="w-5 h-5 text-yellow-400 mb-1"></i>
                    <span class="text-[10px] text-gray-400 uppercase">Pending</span>
                    <span class="font-bold text-yellow-400">{{ $pending }}</span>
                </div>

                <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-red-500/10 transition-all">
                    <i data-lucide="user-x" class="w-5 h-5 text-red-400 mb-1"></i>
                    <span class="text-[10px] text-gray-400 uppercase">Disconnected</span>
                    <span class="font-bold text-red-400">{{ $disconnected }}</span>
                </div>

                <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-gray-500/10 transition-all">
                    <i data-lucide="user" class="w-5 h-5 text-gray-500 mb-1"></i>
                    <span class="text-[10px] text-gray-400 uppercase">Never</span>
                    <span class="font-bold text-gray-400">{{ $never }}</span>
                </div>

            </div>
        </div>


    <!-- Overview Agent -->
        <div class="border border-white p-4 rounded bg-transparent flex flex-col items-center justify-center relative hover-scale animate-fade-in delay-2 overflow-hidden">

            <h3 class="text-sm absolute top-4 left-4 text-white">
                Overview Agent
            </h3>

            @php
                $totalAgent = $active + $pending + $disconnected + $never;
                $activePercent = $totalAgent > 0 ? ($active / $totalAgent) * 100 : 0;
            @endphp

            <div class="w-32 h-32 rounded-full relative flex items-center justify-center"
                style="background:
                conic-gradient(
                #22c55e 0% {{ $activePercent }}%,
                #1f2937 {{ $activePercent }}% 100%
                );">

                <div class="w-20 h-20 bg-[#111315] rounded-full"></div>

            </div>

            @if($totalAgent > 0)
                <p class="mt-4 text-sm font-medium tracking-widest text-green-400 animate-pulse">
                    {{ $totalAgent }} AGENT DETECTED
                </p>
            @else
                <p class="mt-4 text-sm font-medium tracking-widest text-gray-500 animate-pulse">
                    NO AGENT DETECTED
                </p>
            @endif

        </div>



    <!-- Live Scanning -->
        <div class="border border-white p-4 rounded bg-transparent hover-scale animate-fade-in delay-3">

            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm text-white">
                        Threat Trend (7 Days)
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Total Alerts:
                        <span class="text-red-400 font-bold">
                            {{ $totalAlerts }}
                        </span>
                    </p>
                </div>
                <span class="text-[10px] bg-blue-500/20 text-blue-400 px-2 rounded pulse-soft">
                    Live Analytics
                </span>
            </div>

            <div class="h-40">
                <canvas id="alertChart"></canvas>
            </div>

        </div>



    <!-- Table Agent -->

        <div class="md:col-span-3 border border-white rounded overflow-hidden shadow-2xl animate-fade-in delay-3">
            <div class="p-4 flex items-center justify-between border-b border-custom bg-[#2B2D34]">
                <h3 class="font-medium flex items-center gap-2">
                    Agents 
                        <span id="agent-count" class="text-xs bg-gray-700 px-2 py-0.5 rounded text-gray-300">
                            {{ count($items) }}
                        </span>
                </h3>
                <div class="flex items-center gap-6 text-sm">
                    <button onclick="addRandomAgent()" class="flex items-center gap-2 text-white hover:text-blue-400 transition-all hover:translate-x-1">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Deploy new agent
                    </button>
                    <button onclick="refreshData()" class="flex items-center gap-2 text-white hover:text-blue-400 transition-all">
                        <i data-lucide="refresh-cw" class="w-4 h-4" id="refresh-icon"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="p-3 bg-[#212121]">
                <div class="bg-black/40 border border-custom px-3 py-1.5 text-xs text-blue-300 font-mono flex items-center gap-2">
                    <span class="opacity-70">> system_check --status</span>
                    <div class="w-[1px] h-4 bg-blue-400 cursor-blink"></div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#2B2D34] text-white border-b border-white">
                        <tr>
                            <th class="p-3 w-10"><input type="checkbox" class="accent-blue-500"></th>
                            <th class="p-3 font-medium">ID</th>
                            <th class="p-3 font-medium">Name</th>
                            <th class="p-3 font-medium">IP address</th>
                            <th class="p-3 font-medium">Operating system</th>
                            <th class="p-3 font-medium">Status</th>
                            <th class="p-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>

                <tbody class="divide-y divide-custom bg-[#212121]/30">
                    @forelse($items as $agent)
                    <tr class="hover:bg-white/5 transition">

                        <td class="p-3">
                            <input type="checkbox" class="accent-blue-500">
                        </td>
                    
                        <td class="p-3 text-gray-400">
                            {{ $agent['id'] }}
                        </td>
                    
                        <td class="p-3 font-medium text-white">
                            {{ $agent['name'] }}
                        </td>
                    
                        <td class="p-3 text-gray-300">
                            {{ $agent['ip'] }}
                        </td>
                    
                        <td class="p-3 text-gray-300">
                            {{ $agent['os']['name'] ?? '-' }}
                        </td>
                    
                        <td class="p-3">
                            @if($agent['status'] == 'active')
                                <span class="text-green-400 font-semibold">Active</span>
                            @elseif($agent['status'] == 'pending')
                                <span class="text-yellow-400 font-semibold">Pending</span>
                            @elseif($agent['status'] == 'disconnected')
                                <span class="text-red-400 font-semibold">Disconnected</span>
                            @else
                                <span class="text-gray-400 font-semibold">
                                    {{ $agent['status'] }}
                                </span>
                            @endif
                        </td>
                    
                        <td class="p-3 text-right">
                            <button class="text-blue-400 hover:underline">
                                View
                            </button>
                        </td>
                    
                    </tr>
                    
                    @empty
                    
                    <tr>
                        <td colspan="7" class="text-center p-8 text-gray-400">
                            No Agent Found
                        </td>
                    </tr>
                    
                    @endforelse
                    
                    </tbody>
                </table>
            </div>

            <div class="p-3 flex items-center justify-between text-xs text-white border-t border-white bg-[#2B2D34]">
                <span>
                    Showing {{ count($items) }} of {{ count($items) }} entries
                </span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    /* =========================
       TABLE BUTTON ACTION
    ========================= */
    
    function refreshData() {
        location.reload();
    }
    
    function addRandomAgent() {
        alert('Gunakan menu Deploy Agent untuk menambahkan agent baru.');
    }
    
    
    /* =========================
       ALERT CHART (7 DAYS)
    ========================= */
    
    const ctx = document.getElementById('alertChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Alerts',
                    data: @json($chartData),
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(96,165,250,0.15)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
    
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
    
                scales: {
                    x: {
                        ticks: {
                            color: '#d1d5db'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    },
    
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#d1d5db'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }
                }
            }
        });
    }
    </script>
</body>
</html>