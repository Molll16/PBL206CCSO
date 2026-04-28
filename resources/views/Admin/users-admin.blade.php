<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Cyber Security Office - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #2b2d34; color: #e5e7eb; }
        .bg-header { background-color: #1a1c1e; }
        .bg-card { background-color: #2b2d32; }
        .border-custom { border-color: #4a4e54; }
        .table-row-hover:hover { background-color: rgba(255, 255, 255, 0.03); transition: all 0.2s; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
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
        ‹
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
          <a href={{ route('agents-list') }} class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Agent List</a>
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
  </header>

<!-- penutup navbar totalitas bagian atas pertama -->


    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('usersadmin') }}" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Users</a>
            <a href="{{ route('adduser') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Add User</a>
            <a href="{{ route('assignagent') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Assign Agent</a>
        </div>
        <a href="#" class="text-white/60 hover:text-white transition text-sm">Profile Settings</a>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">
        <div class="grid grid-cols-4 gap-4 mb-10 text-center">
            <div>
                <p class="text-lg text-gray-300">Total User</p>
                <h2 class="text-4xl font-bold mt-2">08</h2>
            </div>
            <div>
                <p class="text-lg text-gray-300">User Active</p>
                <h2 class="text-4xl font-bold mt-2 text-white">0</h2>
            </div>
            <div>
                <p class="text-lg text-gray-300">Total Company</p>
                <h2 class="text-4xl font-bold mt-2">0</h2>
            </div>
            <div>
                <p class="text-lg text-gray-300">Agent Assigned</p>
                <h2 class="text-4xl font-bold mt-2">0</h2>
            </div>
        </div>

        <div class="border border-gray-600 rounded-sm bg-transparent overflow-hidden">
            <div class="p-3 flex items-center justify-between border-b border-gray-600">
                <div class="text-sm font-medium">Users</div>
                <div class="flex items-center gap-5 text-xs">
                    <button class="flex items-center gap-1 hover:text-cyan-400 transition">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Add New User
                    </button>
                    <button class="flex items-center gap-1 hover:text-cyan-400 transition">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
                    </button>
                    <button class="flex items-center gap-1 hover:text-cyan-400 transition">
                        <i data-lucide="upload" class="w-4 h-4"></i> Export formatted
                    </button>
                    <button class="flex items-center gap-1 hover:text-cyan-400 transition">
                        More <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </button>
                    <button class="hover:text-cyan-400">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="px-4 py-2 border-b border-gray-600">
                <div class="bg-[#1a1c1e] border border-gray-700 rounded px-3 py-1 text-sm font-mono text-gray-300">
                    status=active
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-600 text-gray-300 uppercase text-[10px] tracking-wider">
                            <th class="p-3 w-8"><input type="checkbox" class="accent-cyan-500"></th>
                            <th class="p-3 font-semibold">ID <i data-lucide="arrow-up" class="w-3 h-3 inline"></i></th>
                            <th class="p-3 font-semibold">Name</th>
                            <th class="p-3 font-semibold">Email</th>
                            <th class="p-3 font-semibold">Phone Number</th>
                            <th class="p-3 font-semibold">Agent Total</th>
                            <th class="p-3 font-semibold">Adding Date</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <tr class="table-row-hover">
                            <td class="p-3"><input type="checkbox" class="accent-cyan-500"></td>
                            <td class="p-3 text-gray-400">001</td>
                            <td class="p-3">Mirjak</td>
                            <td class="p-3">Jake123@company.com</td>
                            <td class="p-3">081234567890</td>
                            <td class="p-3 underline text-cyan-400 cursor-pointer">1</td>
                            <td class="p-3 text-gray-400">UTC+7 23:00:19 10/04/26</td>
                            <td class="p-3">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span> active</span>
                            </td>
                            <td class="p-3 text-right text-gray-400">
                                <button class="hover:text-white mx-1"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                <button class="hover:text-white mx-1"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr class="table-row-hover">
                            <td class="p-3"><input type="checkbox" class="accent-cyan-500"></td>
                            <td class="p-3 text-gray-400">002</td>
                            <td class="p-3">Maulana</td>
                            <td class="p-3">Moll16@gmail.com</td>
                            <td class="p-3">089876543210</td>
                            <td class="p-3 underline text-cyan-400 cursor-pointer">0</td>
                            <td class="p-3 text-gray-400">GMT+7 14:13:12 12/01/26</td>
                            <td class="p-3">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> deactive</span>
                            </td>
                            <td class="p-3 text-right text-gray-400">
                                <button class="hover:text-white mx-1"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                <button class="hover:text-white mx-1"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr class="table-row-hover">
                            <td class="p-3"><input type="checkbox" class="accent-cyan-500"></td>
                            <td class="p-3 text-gray-400">003</td>
                            <td class="p-3">Bintang</td>
                            <td class="p-3">Bintangdwi190@gmail.com</td>
                            <td class="p-3">080808080808</td>
                            <td class="p-3 underline text-cyan-400 cursor-pointer">0</td>
                            <td class="p-3 text-gray-400">UTC+4 20:51:11 12/04/26</td>
                            <td class="p-3">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> deactive</span>
                            </td>
                            <td class="p-3 text-right text-gray-400">
                                <button class="hover:text-white mx-1"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                <button class="hover:text-white mx-1"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr class="table-row-hover">
                            <td class="p-3"><input type="checkbox" class="accent-cyan-500"></td>
                            <td class="p-3 text-gray-400">007</td>
                            <td class="p-3">anonim</td>
                            <td class="p-3">anonim@yahoo.id</td>
                            <td class="p-3">080000000001</td>
                            <td class="p-3 underline text-cyan-400 cursor-pointer">0</td>
                            <td class="p-3 text-gray-400">GMT+7 06:59:27 01/04/26</td>
                            <td class="p-3">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> missing data</span>
                            </td>
                            <td class="p-3 text-right text-gray-400">
                                <button class="hover:text-white mx-1"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                <button class="hover:text-white mx-1"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-4 flex items-center justify-between text-xs text-gray-400 border-t border-gray-600">
                <div class="flex items-center gap-2">
                    Rows per page: 
                    <select class="bg-transparent border border-gray-600 rounded px-1">
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
      menu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
    }

    // Tutup dropdown Manage jika klik di luar
    document.addEventListener('click', function(e) {
      const menu = document.getElementById('manage-menu');
      const btn = menu.previousElementSibling;
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
        document.getElementById('arrow-manage').classList.remove('rotate-180');
      }
    });
// penutup fungsi untuk navbar pertama

        // Inisialisasi icon lucide
        lucide.createIcons();
    </script>
</body>
</html>