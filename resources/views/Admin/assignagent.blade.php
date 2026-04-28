<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Cyber Security Office - Assign Agent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(resources/js/app.js)
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #2b2d32; color: #e5e7eb; }
        .bg-header { background-color: #1a1c1e; }
        .select-dark { 
            background-color: #212121; 
            border: 1px solid #4a4e54; 
            color: #9ca3af;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
        }
        .select-dark:focus {
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
<body class="min-h-screen">

@include('components.header-admin')
        
        <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('usersadmin') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Users</a>
            <a href="{{ route('adduser') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Add User</a>
            <a href="{{ route('assignagent') }}" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Assign Agent</a>
        </div>
        <a href="#" class="text-white/60 hover:text-white transition text-sm">Profile Settings</a>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">
        <div class="grid grid-cols-4 gap-4 mb-14 text-center">
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
            <h3 class="text-sm mb-4 font-medium">Assign An Agent</h3>
            
            <div class="w-full max-w-md border border-white rounded-sm p-10 bg-transparent">
                <form class="space-y-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs text-gray-300">Agent name <span class="text-red-500">*</span></label>
                        <select class="select-dark px-3 py-2 rounded-sm w-full text-sm">
                            <option value="" disabled selected>Agent name</option>
                            <option value="agent-001">Agent 001</option>
                            <option value="agent-002">Agent 002</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs text-gray-300">Assigned to <span class="text-red-500">*</span></label>
                        <select class="select-dark px-3 py-2 rounded-sm w-full text-sm">
                            <option value="" disabled selected>Assigned to</option>
                            <option value="user-mirjak">Mirjak</option>
                            <option value="user-maulana">Maulana</option>
                        </select>
                    </div>

                    <div class="flex justify-center pt-4">
                        <button type="submit" class="bg-[#3b82f6] hover:bg-blue-600 transition text-white text-sm font-medium px-10 py-2 rounded-md shadow-lg">
                            Add
                        </button>
                    </div>
                </form>
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