<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('Admin.components.header-admin')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="relative bg-[#2B2D34] px-6 flex items-center justify-center border-b-2 border-white">
    <div class="flex gap-8">
        <a href="{{ route('profile-overview-admin') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Overview</a>
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
      <div class="p-6 rounded">

        <!-- Nama dan nomor telepon pengguna -->
        <h2 class="mt-2 font-semibold">User Tester ABCD</h2>
        <p class="text-sm text-gray-400">+62888888888</p>

        <!-- Daftar perusahaan yang diikuti pengguna -->
        <div class="mt-10 text-sm text-gray-400">
            <div class="flex justify-between">
                <span>Total Customer:</span>
                <span class="text-white">3</span>
            </div>
            <div class="flex justify-between">
                <span>Total Server:</span>
                <span class="text-white">3</span>
            </div>
            <div class="flex justify-between">
                <span>Total Agent:</span>
                <span class="text-white">8</span>
            </div>
            <div class="flex justify-between">
                <span>Total Dashboard:</span>
                <span class="text-white">6</span>
            </div>
        </div>    

        <p class="mt-20 text-[10px] text-gray-500 italic">
                User Assigned by you: 2
        </p>
      </div>
    </div>


    <!-- KOLOM KANAN: Form pengaturan profil -->
    <div class="w-2/3 space-y-6 pl-10">
        
        <div class="space-y-4">
    <h3 class="text-lg font-medium">Agent Log</h3>
    <div class="flex items-center border border-white rounded-lg bg-[#1a1a1a]/30 overflow-hidden">
        
        <div class="flex-1 p-8 text-center">
            <p class="text-xs text-gray-400">Total Agent</p>
            <p class="text-2xl font-bold">08</p>
        </div>

        <div class="w-[1px] h-12 bg-gray-700"></div>

        <div class="flex-1 p-8 text-center">
            <p class="text-xs text-gray-400">Assigned Agent</p>
            <p class="text-2xl font-bold">03</p>
        </div>

        <div class="w-[1px] h-12 bg-gray-700"></div>

        <div class="flex-1 p-8 text-center">
            <p class="text-xs text-gray-400">Unassigned Agent</p>
            <p class="text-2xl font-bold">05</p>
        </div>
        
    </div>
</div>

        <div class="space-y-4">
            <h3 class="text-lg font-medium">Agents</h3>
            <div class="border border-white rounded-lg bg-[#1a1a1a]/40 overflow-hidden">
                
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Agents (8)</span>
                        <button class="opacity-60 hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 24" strokeWidth={1.5} stroke="currentColor" className="size-6" class="w-5 h-5">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                          </svg>


                        </button>
                    </div>
                    <input type="text" value="status=active" 
                           class="w-full bg-[#2b2d34]/50 border border-gray-600 rounded px-3 py-1.5 text-xs text-gray-300 focus:outline-none focus:border-cyan-500">
                </div>

                <table class="w-full text-left text-[11px]">
                    <thead class="text-gray-400 border-t border-b border-gray-700">
                        <tr>
                            <th class="px-4 py-3"><input type="checkbox" class="rounded bg-transparent border-gray-500"></th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">ID ↑</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">IP Address</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">Assigned To</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-semibold uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4"><input type="checkbox" class="rounded bg-transparent border-gray-500"></td>
                            <td class="px-4 py-4 text-gray-400">001</td>
                            <td class="px-4 py-4 text-cyan-400">Windows 11</td>
                            <td class="px-4 py-4 text-gray-300">192.168.33.129</td>
                            <td class="px-4 py-4 text-cyan-400">Sadek</td>
                            <td class="px-4 py-4">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> active
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-center gap-4 opacity-70">
                                    <button class="hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    </button>
                                    <button class="hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                    </button>
                                    <button class="hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
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


    //sidebar and tombol manage
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