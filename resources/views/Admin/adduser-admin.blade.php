<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Cyber Security Office - Add User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #2b2d34; color: #e5e7eb; }
        .bg-header { background-color: #1a1c1e; }
        .border-custom { border-color: #4a4e54; }
        .input-dark { 
            background-color: #212121; 
            border: 1px solid #4a4e54; 
            color: #9ca3af;
            font-size: 0.875rem;
        }
        .input-dark:focus {
            border-color: #22d3ee;
            outline: none;
            color: white;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    </style>
</head>
<body class="min-h-screen bg-[#2B2D34]">

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
          <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Information</a>
          <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Deployment</a>
        </div>
      </div>

    </nav>
  </aside>

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
            <img id="arrow-manage" src="/db/arrow.png" class="w-3 transition-transform duration-200">
          </button>

          <!-- Dropdown Menu -->
          <div id="manage-menu"
               class="hidden absolute right-0 mt-2 w-32 bg-[#1e1e1e] border rounded-md shadow-xl overflow-hidden">
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Profile Settings</a>
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Logout</a>
          </div>
        </div>

        <button class="flex items-center justify-center w-9 h-9 border border-white rounded-md text-lg hover:bg-[#2c2c2c] transition">
          +
        </button>
      </div>

    </div>
  </header>

        <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="/usersadmin" class="py-3 text-gray-400 text-sm hover:text-white transition">Users</a>
            <a href="/adduseradmin" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Add User</a>
            <a href="#" class="py-3 text-gray-400 text-sm hover:text-white transition">Assign Agent</a>
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

        <div class="flex flex-col items-center">
            <h3 class="text-sm mb-4 font-medium">Add New User</h3>
            
            <div class="w-full max-w-2xl border border-gray-600 rounded-sm p-8 bg-transparent">

            
                <form action="/customers" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs text-gray-300">Email<span class="text-red-500">*</span></label>
                            <input type="email" name="email" placeholder="Email" class="input-dark px-3 py-2 rounded-sm w-full">
                        </div>
                        
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs text-gray-300">Nama Lengkap<span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Nama Lengkap" class="input-dark px-3 py-2 rounded-sm w-full">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs text-gray-300">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" placeholder="Password" class="input-dark px-3 py-2 pr-10 rounded-sm w-full">
                                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                                    <i data-lucide="wand-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs text-gray-300">Username<span class="text-red-500">*</span></label>
                            <input type="text" name="username" placeholder="Username" class="input-dark px-3 py-2 rounded-sm w-full">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 pt-2">
                        <label class="text-xs text-gray-300">Phone Number</label>
                        <input type="text" name="no_telp" placeholder="Phone Number" class="input-dark px-3 py-2 rounded-sm w-1/2">
                    </div>

                    <div class="flex justify-center mt-6">
                        <button type="submit" class="bg-[#3b82f6] hover:bg-blue-600 transition text-white text-sm font-medium px-8 py-2 rounded-md shadow-lg">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Inisialisasi icon lucide
        lucide.createIcons();

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
        // end sidebar
    </script>
</body>
</html>