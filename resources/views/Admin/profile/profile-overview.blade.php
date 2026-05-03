<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/js/app.js')
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
      
      <h3 class="text-lg font-medium">Total</h3>
      
      <div class="grid grid-cols-4 gap-4 p-6 border border-white rounded-lg bg-[#1a1a1a]/30">
        <div class="text-center">
            <p class="text-xs text-gray-400 mb-2">Total Customer</p>
            <p class="text-2xl font-bold">03</p>
        </div>
        <div class="text-center border-l border-gray-700">
            <p class="text-xs text-gray-400 mb-2">Total Server</p>
            <p class="text-2xl font-bold">03</p>
        </div>
        <div class="text-center border-l border-gray-700">
            <p class="text-xs text-gray-400 mb-2">Total Agent</p>
            <p class="text-2xl font-bold">08</p>
        </div>
        <div class="text-center border-l border-gray-700">
            <p class="text-xs text-gray-400 mb-2">Total Dashboard</p>
            <p class="text-2xl font-bold">11</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
            <h4 class="text-sm font-medium">Customer Stat</h4>
            <div class="border border-white rounded-lg p-2 bg-[#1a1a1a]/50 h-48 flex items-center justify-center">
                <p class="text-gray-600 text-xs">[ Area Grafik Customer ]</p>
                </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-sm font-medium">Server Stat</h4>
            <div class="border border-white rounded-lg p-2 bg-[#1a1a1a]/50 h-48 flex items-center justify-center">
                <p class="text-gray-600 text-xs">[ Area Grafik Server ]</p>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-sm font-medium">Agent Stat</h4>
            <div class="border border-white rounded-lg p-2 bg-[#1a1a1a]/50 h-48 flex items-center justify-center">
                <p class="text-gray-600 text-xs">[ Area Grafik Agent ]</p>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-sm font-medium">Dashboard Stat</h4>
            <div class="border border-white rounded-lg p-2 bg-[#1a1a1a]/50 h-48 flex items-center justify-center">
                <p class="text-gray-600 text-xs">[ Area Grafik Dashboard ]</p>
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