<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Security Dashboard - Interactive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        <div class="grid grid-cols-4 gap-4 mb-8 text-center">
            <div class="group cursor-default animate-fade-in delay-1">
                <p class="text-sm text-white group-hover:text-blue-400 transition-colors">All Agent Active</p>
                <h2 class="text-4xl font-bold mt-1" id="stat-active">00</h2>
            </div>
            <div class="group cursor-default animate-fade-in delay-1">
                <p class="text-sm text-white group-hover:text-yellow-400 transition-colors">All Agent Pending</p>
                <h2 class="text-4xl font-bold mt-1" id="stat-pending">00</h2>
            </div>
            <div class="group cursor-default animate-fade-in delay-2">
                <p class="text-sm text-white group-hover:text-red-400 transition-colors">All Agent Disconnected</p>
                <h2 class="text-4xl font-bold mt-1" id="stat-disconnected">00</h2>
            </div>
            <div class="group cursor-default animate-fade-in delay-2">
                <p class="text-sm text-white">All Agent Never Connected</p>
                <h2 class="text-4xl font-bold mt-1 text-gray-500">00</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="border border-white p-4 rounded bg-transparent hover-scale animate-fade-in delay-1">
                <h3 class="text-sm mb-4 text-white">Agent by status</h3>
                <div class="grid grid-cols-2 grid-rows-2 h-40 border-l border-t border-custom">
                    <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-green-500/10 transition-all">
                        <i data-lucide="user-check" class="w-5 h-5 text-green-400 mb-1"></i>
                        <span class="text-[10px] text-gray-400 uppercase">Active</span>
                        <span class="font-bold">0</span>
                    </div>
                    <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-yellow-500/10 transition-all">
                        <i data-lucide="user-minus" class="w-5 h-5 text-yellow-400 mb-1"></i>
                        <span class="text-[10px] text-gray-400 uppercase">Pending</span>
                        <span class="font-bold">0</span>
                    </div>
                    <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-red-500/10 transition-all">
                        <i data-lucide="user-x" class="w-5 h-5 text-red-400 mb-1"></i>
                        <span class="text-[10px] text-gray-400 uppercase">Disconnected</span>
                        <span class="font-bold">0</span>
                    </div>
                    <div class="border-r border-b border-custom flex flex-col items-center justify-center p-2 hover:bg-gray-500/10 transition-all">
                        <i data-lucide="user" class="w-5 h-5 text-gray-500 mb-1"></i>
                        <span class="text-[10px] text-gray-400 uppercase">Never</span>
                        <span class="font-bold">0</span>
                    </div>
                </div>
            </div>

            <div class="border border-white p-4 rounded bg-transparent flex flex-col items-center justify-center relative hover-scale animate-fade-in delay-2 overflow-hidden">
                <h3 class="text-sm absolute top-4 left-4 text-white">Overview Agent</h3>
                <div class="w-32 h-32 rounded-full border-[16px] border-[#111315] relative transition-transform duration-1000 hover:rotate-180" 
                     style="border-top-color: #333; border-right-color: #333; border-bottom-color: #333; border-left-color: #333;">
                </div>
                <p class="mt-4 text-sm font-medium tracking-widest text-gray-500 animate-pulse">NO AGENT DETECTED</p>
            </div>

            <div class="border border-white p-4 rounded bg-transparent hover-scale animate-fade-in delay-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm text-white">Statistic</h3>
                    <span class="text-[10px] bg-blue-500/20 text-blue-400 px-2 rounded pulse-soft">Live Scanning</span>
                </div>
                <div class="h-40 w-full relative">
                    <svg id="live-chart" viewBox="0 0 100 50" class="w-full h-full">
                        <path d="M 0 50 L 100 50 M 0 40 L 100 40 M 0 30 L 100 30 M 0 20 L 100 20 M 0 10 L 100 10" stroke="#333" stroke-width="0.2"/>
                        <polyline id="chart-line" points="0,50 100,50" fill="none" stroke="#60a5fa" stroke-width="1" class="transition-all duration-1000" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="border border-white rounded overflow-hidden shadow-2xl animate-fade-in delay-3">
            <div class="p-4 flex items-center justify-between border-b border-custom bg-[#2B2D34]">
                <h3 class="font-medium flex items-center gap-2">
                    Agents <span id="agent-count" class="text-xs bg-gray-700 px-2 py-0.5 rounded text-gray-300">0</span>
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
                    <tbody id="agent-table-body" class="divide-y divide-custom bg-[#212121]/30">
                        </tbody>
                </table>
            </div>

            <div class="p-3 flex items-center justify-between text-xs text-white border-t border-white bg-[#2B2D34]">
                <span>Showing <span id="current-rows">0</span> of 0 entries</span>
            </div>
        </div>
    </div>

    <script>
// Buat fungsi untuk navbar pertama
        //sidebar toggle
        
    function openSidebar() {
      document.getElementById('sidebar').classList.remove('-translate-x-full');
      document.getElementById('sidebar').classList.add('translate-x-0');
      document.getElementById('sidebar-backdrop').classList.remove('opacity-0', 'pointer-events-none');
      document.getElementById('sidebar-backdrop').classList.add('opacity-100');
    }

    function closeSidebar() {
      document.getElementById('sidebar').classList.add('-translate-x-full');
      document.getElementById('sidebar').classList.remove('translate-x-0');
      document.getElementById('sidebar-backdrop').classList.add('opacity-0', 'pointer-events-none');
      document.getElementById('sidebar-backdrop').classList.remove('opacity-100');
    }
// end sidebar

    function toggleSubmenu(menuId, chevronId) {
      document.getElementById(menuId).classList.toggle('hidden');
      document.getElementById(chevronId).classList.toggle('rotate-180');
    }

    function toggleManage() {
      const menu = document.getElementById('manage-menu');
      const arrow = document.getElementById('arrow-manage');
      const chevron = document.getElementById('chevron-manage');
        menu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
        chevron.classList.toggle('rotate-180');
    }

    // Tutup dropdown Manage jika klik di luar
    document.addEventListener('click', function(e) {
      const menu = document.getElementById('manage-menu');
      const btn = menu.previousElementSibling;
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
        document.getElementById('arrow-manage').classList.remove('rotate-180');
        document.getElementById('chevron-manage').classList.remove('rotate-180');
      }
    });
// penutup fungsi untuk navbar pertama


        // Data dikosongkan sesuai permintaan
        let agentsData = [];

        function renderTable(data) {
            const tbody = document.getElementById('agent-table-body');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center opacity-40 animate-pulse">
                                <i data-lucide="search-x" class="w-12 h-12 mb-3"></i>
                                <p class="text-lg font-medium">Agent not found</p>
                                <p class="text-xs">No active security agents detected in this sector.</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                data.forEach((agent, index) => {
                    const statusColor = agent.status === 'active' ? 'bg-green-500' : 'bg-red-500';
                    const row = `
                        <tr class="table-row-hover group/row animate-fade-in" style="animation-delay: ${index * 0.05}s">
                            <td class="p-3"><input type="checkbox" class="accent-blue-500"></td>
                            <td class="p-3 text-gray-500 font-mono">${agent.id}</td>
                            <td class="p-3 font-medium text-blue-100">${agent.name}</td>
                            <td class="p-3 font-mono text-xs text-gray-400">${agent.ip}</td>
                            <td class="p-3 flex items-center gap-2">
                                <i data-lucide="${agent.osIcon}" class="w-4 h-4 text-gray-500"></i> ${agent.os}
                            </td>
                            <td class="p-3">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full ${statusColor} shadow-[0_0_8px_rgba(34,197,94,0.4)] animate-pulse"></span> 
                                    ${agent.status}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-3 text-gray-500 opacity-0 group-hover/row:opacity-100 transition-opacity">
                                    <button class="hover:text-blue-400"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                    <button class="hover:text-white"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            }
            lucide.createIcons();
            document.getElementById('current-rows').innerText = data.length;
            document.getElementById('agent-count').innerText = data.length;
        }

        function updateLiveChart() {
            const polyline = document.getElementById('chart-line');
            if(agentsData.length === 0) {
                polyline.setAttribute('points', '0,50 100,50');
                return;
            }
            let points = "";
            for (let i = 0; i <= 10; i++) {
                const y = Math.floor(Math.random() * 30) + 10;
                points += `${i * 10},${y} `;
            }
            polyline.setAttribute('points', points);
        }

        function refreshData() {
            const icon = document.getElementById('refresh-icon');
            icon.classList.add('animate-spin');
            setTimeout(() => {
                updateLiveChart();
                icon.classList.remove('animate-spin');
            }, 800);
        }

        function addRandomAgent() {
            const newId = (agentsData.length + 1).toString().padStart(3, '0');
            agentsData.push({
                id: newId,
                name: "New-Agent-" + newId,
                ip: `192.168.1.${Math.floor(Math.random() * 254)}`,
                os: "Linux Kernel 6.x",
                osIcon: "terminal",
                status: "active"
            });
            renderTable(agentsData);
            updateLiveChart();
        }

        window.onload = () => {
            renderTable(agentsData);
            setInterval(updateLiveChart, 3000);
        };
    </script>
</body>
</html>