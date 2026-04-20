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
        body { font-family: 'Inter', sans-serif; background-color: #25282c; color: #e5e7eb; }
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

    <header class="bg-[#212121] border-b-2 border-white animate-fade-in">
        <div class="flex items-center h-16">
            <button class="flex items-center justify-center w-16 h-16 border-r-4 border-white hover:bg-white/10 transition-colors">
                <div class="w-8 h-8 border-2 border-white rounded-lg flex items-center justify-center">
                    <img src="/ob/sidebar.png" class="w-3 h-3" alt="">
                </div>
            </button>

            <button class="flex items-center justify-center w-16 h-16 hover:bg-white/10 transition-colors">
                <img src="/ob/home.png" class="w-8" alt="">
            </button>

            <div class="flex items-center gap-3 px-2 animate-slide-in">
                <img src="/ob/logo.png" class="w-6" alt="">
                <div class="font-tahoma">
                    <p class="text-xs tracking-wide">Central Cyber</p>
                    <p class="text-xs tracking-wide">Security Office</p>
                </div>
            </div>

            <div class="flex-1"></div>
        </div>
    </header>


    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('usersadmin') }}" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Users</a>
            <a href="{{ route('adduser') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Add User</a>
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
        // Inisialisasi icon lucide
        lucide.createIcons();
    </script>
</body>
</html>