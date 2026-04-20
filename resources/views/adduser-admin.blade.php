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
        body { font-family: 'Inter', sans-serif; background-color: #2b2d32; color: #e5e7eb; }
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
    </script>
</body>
</html>