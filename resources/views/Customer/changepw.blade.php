<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Overview</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

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
            <a href="/login" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Logout</a>
          </div>
        </div>

        <button class="flex items-center justify-center w-9 h-9 border border-white rounded-md text-lg hover:bg-[#2c2c2c] transition">
          +
        </button>
      </div>

    </div>
  </header>

  <main class="flex-1 flex flex-col">
    <div class="p-2 underline underline-offset-8">
      <a href="/dashboarddft" class="text-white">Password Recreate</a>
    </div>

    <div class="flex-1 flex items-center justify-center">
      <div class="bg-[#212121] rounded-2xl border-4 border-white px-10 py-6 flex flex-col items-center w-96 mb-12">

        <img src="/logindark/logo.png" width="100" height="100" class="mb-4" alt="Logo">

        <div class="w-full space-y-3">

          <div>
            <h1 class="font-bold text-sm mb-1">Your Current Password:</h1>
            <div class="flex items-center gap-1">
              <div class="bg-[#2B2D34] border-2 border-white rounded-lg p-2">
                <img src="/logindark/logopassword.png" width="28" height="28" alt="">
              </div>
              <div class="relative w-full">
                <input id="current_pwd" type="password" placeholder="Current Password"
                  class="w-full rounded-md px-3 py-2 pr-9 text-sm bg-[#2B2D34] text-gray-200 border-[1.5px] border-[#c8dff0] focus:outline-none">
                <span onclick="togglePassword('current_pwd', 'eye1')" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
                  <img id="eye1" src="/logindark/logomata.png" width="16" height="16" alt="">
                </span>
              </div>
            </div>
          </div>

          <div>
            <h1 class="font-bold text-sm mb-1">Your New Password:</h1>
            <div class="flex items-center gap-1">
              <div class="bg-[#2B2D34] border-2 border-white rounded-lg p-2">
                <img src="/logindark/logopassword.png" width="28" height="28" alt="">
              </div>
              <div class="relative w-full">
                <input id="new_pwd" type="password" placeholder="New Password"
                  class="w-full rounded-md px-3 py-2 pr-9 text-sm bg-[#2B2D34] text-gray-200 border-[1.5px] border-[#c8dff0] focus:outline-none">
                <span onclick="togglePassword('new_pwd', 'eye2')" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
                  <img id="eye2" src="/logindark/logomata.png" width="16" height="16" alt="">
                </span>
              </div>
            </div>
          </div>

          <div>
            <div class="flex items-center gap-1">
              <div class="bg-[#2B2D34] border-2 border-white rounded-lg p-2">
                <img src="/logindark/logopassword.png" width="28" height="28" alt="">
              </div>
              <div class="relative w-full">
                <input id="confirm_pwd" type="password" placeholder="Confirm New Password"
                  class="w-full rounded-md px-3 py-2 pr-9 text-sm bg-[#2B2D34] text-gray-200 border-[1.5px] border-[#c8dff0] focus:outline-none">
                <span onclick="togglePassword('confirm_pwd', 'eye3')" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
                  <img id="eye3" src="/logindark/logomata.png" width="16" height="16" alt="">
                </span>
              </div>
            </div>
          </div>

        </div>

        <button class="mt-6 w-32 py-2 rounded-md text-white text-sm bg-[#2a7db5] hover:bg-blue-600 transition-colors">
          Update
        </button>
      </div>
    </div>
  </main>

  <footer class="bg-[#212121] px-10 py-4 border-t-2 border-gray-600">
    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
      <img src="/lp/logo.png" class="h-10" alt="">
      <p class="text-white">© 2026 CCSO, Inc.</p>
      <img src="/lp/garis.png" class="h-5" alt="">
      <p class="text-white">Contact Us</p>
      <img src="/lp/garis.png" class="h-5" alt="">

      <div class="flex items-center gap-2">
        <img src="/lp/telp.png" class="h-5" alt="">
        <p>+62 1234567890</p>
      </div>

      <div class="flex items-center gap-6 ml-2">
        <img src="/lp/tt.png" class="h-5 cursor-pointer" alt="">
        <img src="/lp/ig.png" class="h-5 cursor-pointer" alt="">
        <img src="/lp/wa.png" class="h-5 cursor-pointer" alt="">
        <img src="/lp/email.png" class="h-5 cursor-pointer" alt="">
      </div>

      <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
        <input type="email" placeholder="Sent to our Email..." class="px-3 py-1 text-sm w-56 text-gray-800 focus:outline-none">
        <button class="bg-blue-500 px-3 py-1 text-white hover:bg-blue-600">›</button>
      </div>
    </div>
  </footer>

  <script>
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);

      if (input.type === 'password') {
        input.type = 'text';
        icon.src = '/logindark/logomataopen.png';
      } else {
        input.type = 'password';
        icon.src = '/logindark/logomata.png';
      }
    }

    //sidebar dan manage
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