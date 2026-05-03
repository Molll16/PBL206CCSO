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

      <!-- Input Nama Lengkap -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Full Name</label>
        <input
          type="text"
          value="User Tester ABCD"
          class="bg-transparent border border-white rounded px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-400"
        >
      </div>

      <!-- Input Email -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Email</label>
        <input
          type="email"
          value="testerabcd@gmail.com"
          class="bg-transparent border border-white rounded px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-400"
        >
      </div>

      <!-- Input Nomor Telepon -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Personal Phone Number</label>
        <input
          type="text"
          value="+62 12345678"
          class="bg-transparent border border-white rounded px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-400"
        >
      </div>

      <!-- Input Password dengan tombol lihat/sembunyikan -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Password</label>
        <div class="flex items-center border border-white rounded px-4 py-2">
          <input
            type="password"
            value="12345678"
            id="password"
            class="bg-transparent flex-1 text-sm text-gray-300 focus:outline-none"
          >
          <!-- Tombol tampilkan atau sembunyikan password -->
          <button onclick="togglePassword()" class="text-gray-400 hover:text-white ml-2">
            <img id="eyeIcon" src="/logindark/logomataopen.png">
          </button>
        </div>
        <!-- Tombol untuk mengubah password -->
        <button class="mt-1 bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded w-fit">
          Change Password
        </button>
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