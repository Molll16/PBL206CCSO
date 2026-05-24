<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/jss/app.js')
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('Admin.components.header-admin')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="relative bg-[#2B2D34] px-6 flex items-center justify-center border-b-2 border-white">
    <div class="flex gap-8">
        <a href="{{ route('profile-setting-admin') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Profile Settings</a>
        <a href="{{ route('profile-agent') }}" class="py-3 py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Agent</a>
    </div>

    <div class="absolute right-6">
        <a href="/login" class="border border-white/60 rounded px-4 py-1 text-xs hover:bg-white hover:text-black transition inline-block">
            Logout
        </a>
    </div>
  </div>

  <div class="relative">
      <div class="absolute ml-14 mt-[-35px] cursor-pointer group z-10" onclick="document.getElementById('inputGambarProfil').click()">
        <img id="fotoProfil" class="w-48 h-48 rounded-full object-cover" src="/profilee/profile.png">
        <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <span class="text-white text-xs text-center px-2">Ganti Foto</span>
        </div>
      </div>
      <input type="file" id="inputGambarProfil" accept="image/*" class="hidden" onchange="gantiGambarProfil(event)">
  </div>


  <!-- ===== KONTEN UTAMA ===== -->
  <div class="flex p-6 gap-6 flex-1">

    <!-- KOLOM KIRI: Ringkasan singkat profil pengguna -->
    <div class="mt-32">
      <div class="p-10 rounded">
        <!-- Nama dan nomor telepon pengguna -->
        <h2 class="mt-2 font-semibold">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-400">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>

    <!-- KOLOM KANAN: Form pengaturan profil -->
    <div class="w-2/3 space-y-6 pl-10">
        
        <div class="space-y-4">
    <h3 class="text-lg font-medium">Agent Log</h3>
    <div class="flex items-center border border-white rounded-lg bg-[#1a1a1a]/30 overflow-hidden">
        
        <div class="flex-1 p-8 text-center">
            <p class="text-xs text-gray-400">Total Agent</p>
            <p class="text-2xl font-bold">{{ $totalAgents }}</p>
        </div>

        <div class="w-[1px] h-12 bg-gray-700"></div>

        <div class="flex-1 p-8 text-center">
            <p class="text-xs text-gray-400">Assigned Agent</p>
            <p class="text-2xl font-bold">{{ $assignedAgents }}</p>
        </div>

        <div class="w-[1px] h-12 bg-gray-700"></div>
        
    </div>
</div>

        <div class="space-y-4">
            <h3 class="text-lg font-medium">Agents</h3>
            <div class="border border-white rounded-lg bg-[#1a1a1a]/40 overflow-hidden">
                
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Agents ({{ $agents->count() }})</span>
                    </div>

                <table class="w-full text-left text-[11px]">
                    <thead class="text-gray-400 border-t border-b border-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">ID ↑</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">IP Address</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">Assigned To</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($agents as $agent)

                          <tr class="hover:bg-white/5 transition-colors">
                            <!-- CHECKBOX -->
                            <td class="px-4 py-4">
                              <input type="checkbox" class="rounded bg-transparent border-gray-500">
                            </td>
                            <!-- ID -->
                            <td class="px-4 py-4 text-gray-400">
                              {{ $agent['id'] }}
                            </td>
                            <!-- NAME -->
                            <td class="px-4 py-4 text-cyan-400">
                              {{ $agent['name'] }}
                            </td>
                            <!-- IP -->
                            <td class="px-4 py-4 text-gray-300">
                              {{ $agent['ip'] }}
                            </td>
                            <!-- ASSIGNED -->
                            <td class="px-4 py-4 text-cyan-400">
                              @php
                                $assigned = \App\Models\Agen::where(
                                  'id_wazuh_agen',
                                  $agent['id']
                                )->first();
                              @endphp

                              {{ $assigned->user->name ?? '-' }}
                            </td>
                            <!-- STATUS -->
                            <td class="px-4 py-4">
                              @if($agent['status'] === 'active')

                                <span class="flex items-center gap-1.5">
                                  <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                  active
                                </span>
                              @elseif($agent['status'] === 'disconnected')

                                <span class="flex items-center gap-1.5">
                                  <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                  disconnected
                                </span>
                              @else

                                <span class="flex items-center gap-1.5">
                                  <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                  {{ $agent['status'] }}
                                </span>
                              @endif
                            </td>
                            <!-- ACTION -->
                            <td class="px-4 py-4">
                              <div class="flex justify-center gap-4 opacity-70">
                                <button class="hover:text-white">
                                  View
                                </button>
                                <button class="hover:text-white">
                                  Edit
                                </button>
                                <button class="hover:text-white">
                                  Delete
                                </button>
                              </div>
                            </td>
                          </tr>

                        @empty

                          <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500">
                              No agents found
                            </td>
                          </tr>

                        @endforelse

                    </tbody>
                </table>

                <div class="p-3 flex justify-between items-center text-[10px] text-gray-500 border-t border-gray-800">
                    <div class="flex items-center gap-2">
                        Rows per page: 
                        <select class="bg-transparent border-none focus:ring-0 text-gray-400 cursor-pointer">
                            <option>5</option>
                            <option>10</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="cursor-pointer">‹</span>
                        <span class="text-cyan-400 font-bold underline">1</span>
                        <span class="cursor-pointer">2</span>
                        <span class="cursor-pointer">›</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>


  <!-- ===== JAVASCRIPT ===== -->
  <script>

    // Fungsi untuk menampilkan atau menyembunyikan teks password
    function togglePassword() {
      const input = document.getElementById('password')
      const icon = document.getElementById('eyeIcon')
      if (input.type === 'password') {
        input.type = 'text'
        icon.src = '/logindark/logomataopen.png'
      } else {
        input.type = 'password'
        icon.src = '/logindark/logomata.png'
      }
    }

    // Fungsi untuk mengganti foto profil saat pengguna memilih gambar dari perangkat
    function gantiGambarProfil(event) {
      const file = event.target.files[0]

      // Pastikan pengguna benar-benar memilih file
      if (!file) return

      const reader = new FileReader()

      // Setelah gambar selesai dibaca, langsung tampilkan ke foto profil
      reader.onload = function(e) {
        document.getElementById('fotoProfil').src = e.target.result
      }

      reader.readAsDataURL(file)
    }
  </script>


</body>
</html>