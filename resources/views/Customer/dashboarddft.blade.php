<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Central Cyber Security Office</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">
  
  <!-- backdrop -->
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
        <img src="/ob/logo.png" class="w-6" alt="">
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
      <a href="#"
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
          <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Visualize</a>
          <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Discover</a>
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
          <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Log</a>
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
            <img id="arrow-manage" src="/ob/arrowdown.png" class="w-3 transition-transform duration-200">
          </button>

          <!-- Dropdown Menu -->
          <div id="manage-menu"
               class="hidden absolute right-0 mt-2 w-32 bg-[#1e1e1e] border rounded-md shadow-xl overflow-hidden">
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Profile Settings</a>
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Agent</a>
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Custom Dashboard</a>
            <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Logout</a>
          </div>
        </div>

        <button class="flex items-center justify-center w-9 h-9 border border-white rounded-md text-lg hover:bg-[#2c2c2c] transition">
          +
        </button>
      </div>

    </div>
  </header>

  <main class="flex-1 p-2 relative">

    <div class="mb-4 inline-block border-b-2 border-white pr-4 pb-1">
      <h2 class="text-white text-lg">
        Welcome, <span class="text-[#4DA8DA] cursor-pointer hover:underline">Anonimous</span>!
      </h2>
    </div>

    <div id="pwd-alert" class="absolute top-4 right-2 w-64 bg-[#212121] border border-gray-500 rounded-xl p-5 shadow-2xl z-20">
      <p class="text-[13px] text-gray-300 mb-6 leading-relaxed">
        You must change your current password for a new one
      </p>
      <div class="flex justify-end gap-2">
        <button onclick="dismissAlert()" class="px-4 py-1.5 rounded-md text-xs bg-[#3A3D42] text-gray-300 hover:bg-gray-600 transition">
          Later
        </button>
        <a href="/changepw" class="px-4 py-1.5 rounded-md text-xs bg-[#00A8E8] text-white hover:bg-blue-500 transition">
          Change Now
        </a>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-2 mt-2">

      <div class="col-span-12 lg:col-span-4">
        <p class="text-sm mb-2 ml-1">Feature A</p>
        <div class="border border-white rounded-lg h-56 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature A</span>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-8">
        <p class="text-sm mb-2 ml-1">Feature B</p>
        <div class="border border-white rounded-lg h-56 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature B</span>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-5">
        <p class="text-sm mb-2 ml-1">Feature C</p>
        <div class="border border-white rounded-lg h-48 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature C</span>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-7">
        <p class="text-sm mb-2 ml-1">Feature D</p>
        <div class="border border-white rounded-lg h-48 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Feature D</span>
        </div>
      </div>

      <div class="col-span-12">
        <p class="text-sm mb-2 ml-1">Data Log</p>
        <div class="border border-white rounded-lg h-60 flex items-center justify-center">
          <span class="text-gray-500 font-medium">Data Log</span>
        </div>
      </div>

    </div>
  </main>

  <footer class="bg-[#212121] px-10 py-4 border-t-2 border-gray-600">
    <div id="footer">
      <div class="mt-2 px-1 flex flex-wrap items-center gap-4 text-sm text-gray-400">

        <!-- Logo -->
        <img src="/lp/logo.png" class="h-10" alt="">

        <p class="text-white">© 2026 CCSO, Inc.</p>
        <img src="/lp/garis.png" class="h-5" alt="">

        <p class="text-white">Contact Us</p>
        <img src="/lp/garis.png" class="h-5" alt="">

        <!-- Telepon -->
        <div class="flex items-center gap-2">
          <img src="/lp/telp.png" class="h-5" alt="">
          <p>+62 1234567890</p>
        </div>

        <!-- Media Sosial -->
        <div class="flex items-center gap-6 ml-2">
          <img src="/lp/tt.png" class="h-5 cursor-pointer" alt="">
          <img src="/lp/ig.png" class="h-5 cursor-pointer" alt="">
          <img src="/lp/wa.png" class="h-5 cursor-pointer" alt="">
          <img src="/lp/email.png" class="h-5 cursor-pointer" alt="">
        </div>

        <!-- Email Input -->
        <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
          <input type="email" placeholder="Sent to our Email..." class="px-3 py-1 text-sm w-56 text-gray-800 focus:outline-none">
          <button class="bg-blue-500 px-3 py-1 text-white text-sm hover:bg-blue-600">›</button>
        </div>

      </div>
    </div>
  </footer>

  <script>
    function dismissAlert() {
      const alert = document.getElementById('pwd-alert');
      alert.style.display = 'none';
    }
  </script>

    <script>
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
  </script>

</body>
</html>