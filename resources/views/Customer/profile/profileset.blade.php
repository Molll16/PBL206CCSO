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

@include('Customer.components.header')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="flex items-center py-3 border-b-2">

    <!-- Foto profil yang bisa diklik untuk diganti gambarnya -->
    <div class="absolute ml-14 mt-40 cursor-pointer group" onclick="document.getElementById('inputGambarProfil').click()">

      <!-- Gambar profil utama -->
      <img id="fotoProfil" class="w-48 h-48 rounded-full object-cover" src="/profilee/profile.png">

      <!-- Overlay gelap + teks muncul saat hover -->
      <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
        <span class="text-white text-xs text-center px-2">Ganti Foto</span>
      </div>

    </div>

    <!-- Input file tersembunyi untuk memilih gambar dari perangkat -->
    <input
      type="file"
      id="inputGambarProfil"
      accept="image/*"
      class="hidden"
      onchange="gantiGambarProfil(event)"
    >

    <!-- Menu tab halaman profil -->
    <div class="flex items-center gap-10 ml-[330px]">
      <a href={{ Route('profile-overview') }} class="text-sm cursor-pointer hover:text-blue-400">Overview</a>
      <a href={{ Route('profile-setting') }} class="text-sm cursor-pointer hover:text-blue-400">Profile Settings</a>
      <a href={{ Route('profile-server') }} class="text-sm cursor-pointer hover:text-blue-400">Server</a>
      <a href={{ Route('profile-custom') }} class="text-sm cursor-pointer hover:text-blue-400">Customization Dashboard</a>
    </div>

    <!-- Tombol keluar akun -->
    <div class="ml-auto mr-4">
      <a href="/login" class="border px-4 py-1 rounded text-sm hover:bg-gray-700">Logout</a>
    </div>

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
          <p>Total Server: 3</p>
          <p>Total Dashboard: 3</p>
        </div>

        <!-- Tanggal bergabung pengguna -->
        <p class="mt-6 text-sm text-gray-500">Join Date: 01-10-2025</p>

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
          class="bg-transparent border-2 rounded px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-400"
        >
      </div>

      <!-- Input Email -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Email</label>
        <input
          type="email"
          value="testerabcd@gmail.com"
          class="bg-transparent border-2 rounded px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-400"
        >
      </div>

      <!-- Input Nomor Telepon -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Personal Phone Number</label>
        <input
          type="text"
          value="+62 12345678"
          class="bg-transparent border-2 rounded px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-400"
        >
      </div>

      <!-- Input Password dengan tombol lihat/sembunyikan -->
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold">Password</label>
        <div class="flex items-center border-2 rounded px-4 py-2">
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
        <a href={{ route('changepw') }} class="mt-1 bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded w-fit">
          Change Password
        </a>
      </div>

    </div>

  </div>


  <!-- ===== FOOTER ===== -->
  <footer class="bg-[#212121] px-10 py-4 border-t-2">
    <div class="mt-2 px-1 flex flex-wrap items-center gap-4 text-sm text-gray-400">

      <!-- Logo footer -->
      <img src="/lp/logo.png" class="h-10">

      <!-- Hak cipta -->
      <p class="text-white">© 2026 CCSO, Inc.</p>
      <img src="/lp/garis.png" class="h-5">

      <!-- Tautan hubungi kami -->
      <p class="text-white">Contact Us</p>
      <img src="/lp/garis.png" class="h-5">

      <!-- Nomor telepon kantor -->
      <div class="flex items-center gap-2">
        <img src="/lp/telp.png" class="h-5">
        <p>+62 1234567890</p>
      </div>

      <!-- Ikon media sosial -->
      <div class="flex items-center gap-6 ml-2">
        <img src="/lp/tt.png" class="h-5 cursor-pointer">
        <img src="/lp/ig.png" class="h-5 cursor-pointer">
        <img src="/lp/wa.png" class="h-5 cursor-pointer">
        <img src="/lp/email.png" class="h-5 cursor-pointer">
      </div>

      <!-- Form kirim pesan ke email tim CCSO -->
      <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
        <input type="email" placeholder="Sent to our Email..." class="px-3 py-1 text-sm w-54 text-gray-800">
        <button class="bg-blue-500 px-3 py-1 text-white text-sm hover:bg-blue-600">›</button>
      </div>

    </div>
  </footer>


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