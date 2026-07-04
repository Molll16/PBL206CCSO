<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { page: '#121318', surface: '#1a1b23', borderSubtle: '#262833', textMain: '#f1f3f9', textMuted: '#787f99', brand: '#6366f1', brandHover: '#4f46e5' }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-page text-textMain font-sans antialiased">

    @include('Admin.components.header-admin')

    {{-- ============================================================
    MAIN CONTENT
    ============================================================ --}}
    <div class="p-6 max-w-[1400px] mx-auto">
        <main class="p-8 max-w-7xl mx-auto space-y-6">

            {{-- Statistik Ringkas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                <div class="bg-[#222428] border border-white/10 p-5 rounded-xl">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total User</p>
                    <h2 class="text-4xl font-bold mt-2 text-white">{{ $totalUsers }}</h2>
                </div>
                <div class="bg-[#222428] border border-white/10 p-5 rounded-xl">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Agent Assigned</p>
                    <h2 class="text-4xl font-bold mt-2 text-cyan-400">{{ $totalAssignedAgents }}</h2>
                </div>
            </div>

            {{-- Header Table --}}
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">User Management</h2>
                    <p class="text-sm text-textMuted mt-1">Manage user accounts and information</p>
                </div>
                <a href="{{ route('adduser') }}">
                    <button
                        class="bg-brand hover:bg-brandHover text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition">+
                        Add New User</button>
                </a>
            </div>

            {{-- Data Table --}}
            <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr
                                class="border-b border-borderSubtle bg-page/30 text-[11px] font-bold uppercase text-textMuted">
                                <th class="py-3 px-5 text-left">Nama Lengkap</th>
                                <th class="py-3 px-5">Username</th>
                                <th class="py-3 px-5">Email</th>
                                <th class="py-3 px-5">No HP</th>
                                <th class="py-3 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-borderSubtle text-xs text-textMain">
                            @foreach($users as $user)
                                <tr class="hover:bg-page/40 transition-colors">
                                    {{-- Mengasumsikan properti nama pada table users adalah 'name' atau 'nama_lengkap' --}}
                                    <td class="py-3.5 px-5 text-left font-semibold text-white">
                                        {{ $user->name ?? $user->nama_lengkap ?? '-' }}</td>
                                    <td class="py-3.5 px-5 font-medium text-textMain">{{ $user->username }}</td>
                                    <td class="py-3.5 px-5 text-textMuted">{{ $user->email }}</td>
                                    {{-- Mengasumsikan properti no hp adalah 'phone', 'no_hp', atau 'telepon' --}}
                                    <td class="py-3.5 px-5 text-textMuted">
                                        {{ $user->no_telp ?? $user->no_hp ?? $user->no_telp ?? '-' }}</td>
                                    <td class="py-3.5 px-5 text-right">
                                        <form action="{{ route('customers.destroy', $user->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-500 hover:underline font-medium"
                                                onclick="return confirm('Hapus user ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Inisialisasi ikon Lucide jika dibutuhkan pada layout header
        lucide.createIcons();
    </script>
</body>

</html>