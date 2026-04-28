<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Cyber Security Office - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/js/app.js')
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

@include('components.header-admin')


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
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-600 text-gray-300 uppercase text-[11px] tracking-wider">
                            <th class="p-3 w-8">
                                <input type="checkbox" class="accent-cyan-500">
                            </th>
            
                            <th class="p-3 font-semibold">ID</th>
                            <th class="p-3 font-semibold">User</th>
                            <th class="p-3 font-semibold">Email</th>
                            <th class="p-3 font-semibold">Role</th>
                            <th class="p-3 font-semibold">Company</th>
                            <th class="p-3 font-semibold">Agents</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold">Joined</th>
                            <th class="p-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
            
                    <tbody class="divide-y divide-gray-700/50">
            
                        <tr class="table-row-hover">
                            <td class="p-3">
                                <input type="checkbox" class="accent-cyan-500">
                            </td>
            
                            <td class="p-3 text-gray-400">001</td>
            
                            <td class="p-3">
                                <div class="font-medium text-white">Mirjak</div>
                                <div class="text-xs text-gray-400">+62 81234567890</div>
                            </td>
            
                            <td class="p-3">jake123@company.com</td>
            
                            <td class="p-3">
                                <span class="px-2 py-1 rounded bg-cyan-500/10 text-cyan-400 text-xs">
                                    Customer
                                </span>
                            </td>
            
                            <td class="p-3">PT Alpha Tech</td>
            
                            <td class="p-3">
                                <span class="text-cyan-400 font-medium">2 Assigned</span>
                            </td>
            
                            <td class="p-3">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            </td>
            
                            <td class="p-3 text-gray-400">26 Apr 2026</td>
            
                            <td class="p-3 text-right">
                                <button class="hover:text-cyan-400 mx-1">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="hover:text-cyan-400 mx-1">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>
                                <button class="hover:text-red-400 mx-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
            
            
                        <tr class="table-row-hover">
                            <td class="p-3"><input type="checkbox" class="accent-cyan-500"></td>
            
                            <td class="p-3 text-gray-400">002</td>
            
                            <td class="p-3">
                                <div class="font-medium text-white">Maulana</div>
                                <div class="text-xs text-gray-400">+62 89876543210</div>
                            </td>
            
                            <td class="p-3">moll16@gmail.com</td>
            
                            <td class="p-3">
                                <span class="px-2 py-1 rounded bg-purple-500/10 text-purple-400 text-xs">
                                    Admin
                                </span>
                            </td>
            
                            <td class="p-3">CCSO Internal</td>
            
                            <td class="p-3 text-gray-400">0 Assigned</td>
            
                            <td class="p-3">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                    Inactive
                                </span>
                            </td>
            
                            <td class="p-3 text-gray-400">12 Jan 2026</td>
            
                            <td class="p-3 text-right">
                                <button class="hover:text-cyan-400 mx-1">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="hover:text-cyan-400 mx-1">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>
                                <button class="hover:text-red-400 mx-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
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