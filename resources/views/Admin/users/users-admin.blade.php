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

@include('Admin.components.header-admin')

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('usersadmin') }}" class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Users</a>
            <a href="{{ route('adduser') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Add User</a>
        </div>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">
        <div class="grid grid-cols-2 gap-4 mb-10 text-center">
            <div>
                <p class="text-lg text-gray-300">Total User</p>
                <h2 class="text-4xl font-bold mt-2">{{ $totalUsers }}</h2>
            </div>
            <div>
                <p class="text-lg text-gray-300">Agent Assigned</p>
                <h2 class="text-4xl font-bold mt-2">{{ $totalAssignedAgents }}</h2>
            </div>
        </div>

        <div class="border border-gray-600 rounded-sm bg-transparent overflow-hidden">
            <div class="p-3 flex items-center justify-between border-b border-gray-600">
                <div class="text-sm font-medium">Users</div>
                <div class="flex items-center gap-5 text-xs">
                    <a href="{{ route('adduser') }}"
                       class="flex items-center gap-2 px-4 py-2
                              bg-[#1f2937] hover:bg-cyan-500
                              border border-gray-600 hover:border-cyan-400
                              rounded-lg text-sm text-white
                              transition duration-200 shadow-sm">
                    
                        <i data-lucide="plus-circle"
                           class="w-4 h-4 transition duration-300">
                        </i>
                    
                        Add New User
                    </a>
                    <button onclick="window.location.reload()"
                            class="mr-auto flex items-center gap-2 px-4 py-2 
                                   bg-[#1f2937] hover:bg-cyan-500 
                                   border border-gray-600 hover:border-cyan-400
                                   rounded-lg text-sm text-white 
                                   transition duration-200 shadow-sm">
                    
                        <i data-lucide="refresh-cw"
                           class="w-4 h-4 group-hover:rotate-180 transition duration-500">
                        </i>
                    
                        Refresh
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
            
                            <th class="p-3 font-semibold">User</th>
                            <th class="p-3 font-semibold">Email</th>
                            <th class="p-3 font-semibold">Role</th>
                            <th class="p-3 font-semibold">Agents</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold">Joined</th>
                            <th class="p-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
            
                    <tbody class="divide-y divide-gray-700/50">  
                        @foreach ($users as $user)

                        <tr class="table-row-hover">

                            <td class="p-3">
                                <input type="checkbox" class="accent-cyan-500">
                            </td>

                            <td class="p-3">
                                <div class="font-medium text-white">
                                    {{ $user->name }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    {{ $user->no_telp ?? '-' }}
                                </div>
                            </td>

                            <td class="p-3">
                                {{ $user->email }}
                            </td>

                            <td class="p-3">

                                <span class="px-2 py-1 rounded bg-cyan-500/10 text-cyan-400 text-xs">
                                    {{ ucfirst($user->role) }}
                                </span>

                            </td>

                            <td class="p-3 text-gray-400">
                                {{ $user->agents_count }} Assigned
                            </td>

                            <td class="p-3">
                                <span class="flex items-center gap-2">

                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>

                                    Active
                                </span>
                            </td>

                            <td class="p-3 text-gray-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td class="p-3">

                                <a href="{{ route('customers.edit', $user->id) }}"
                                   class="bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-1 rounded text-sm inline-block">
                                    Edit
                                </a>

                                <form action="{{ route('customers.destroy', $user->id) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm ml-2">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                        @endforeach 
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