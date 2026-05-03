<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Cyber Security Office - Add User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/js/app.js')
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

@include('Admin.components.header-admin')

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
      <div class="flex gap-8">
            <a href="/usersadmin" class="py-3 text-gray-400 text-sm hover:text-white transition">Users</a>
            <a href="/adduseradmin" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Add User</a>
      </div>
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

        <!-- Menampilkan error jika ada -->
          @if($errors->any())
              <div class="bg-red-500 text-white text-sm p-3 rounded mb-4">
                  @foreach($errors->all() as $error)
                      <p>• {{ $error }}</p>
                  @endforeach
              </div>
          @endif

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

    </script>
</body>
</html>