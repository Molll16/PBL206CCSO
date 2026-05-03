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
        body { 
             background-color: #2b2d34; color: #e5e7eb; }
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

@include('Customer.components.header')

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('daftarlog') }}" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">View Logs</a>
        </div>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">

        <div class="border border-white rounded-sm bg-transparent overflow-hidden">
    <div class="p-3 flex items-center justify-between border-b border-white">
        <div class="text-sm font-medium flex items-center gap-1">Logs</div>
        <div class="flex items-center gap-5 text-xs">
            <button class="flex items-center gap-1 hover:text-cyan-400 transition">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
            </button>
            <button class="hover:text-cyan-400">
                <i data-lucide="settings" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-gray-600 text-center text-gray-300 text-[10px] tracking-wider">
                    <th class="p-3 font-semibold">Time</th>
                    <th class="p-3 font-semibold">Severity</th>
                    <th class="p-3 font-semibold">Type</th>
                    <th class="p-3 font-semibold">Server</th>
                    <th class="p-3 font-semibold">Source IP</th>
                    <th class="p-3 font-semibold">Summary</th>
                    <th class="p-3 font-semibold">Status</th>
                    <th class="p-3 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50 text-center">
                <tr class="table-row-hover">
                    <td class="p-3 text-gray-400">08:10</td>
                    <td class="p-3">High</td>
                    <td class="p-3">Brute Force</td>
                    <td class="p-3">Web-01</td>
                    <td class="p-3">45.x.x.x</td>
                    <td class="p-3">Multiple login failed</td>
                    <td class="p-3">Open</td>
                    <td class="p-3">
                        <button class="text-white hover:underline cursor-pointer">View Details</button>
                    </td>
                </tr>
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