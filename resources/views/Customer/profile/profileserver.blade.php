<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Overview</title>
  <script src="https://cdn.tailwindcss.com"></script>
  
  @vite('resources/js/app.js')
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('Customer.components.header')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="flex items-center py-3 border-b-2">

    <!-- Foto profil yang menggantung ke bawah -->
    <img class="w-48 h-48 mt-40 ml-14 absolute" src="/profilee/profile.png">

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


    <!-- KOLOM KANAN: Data perusahaan pengguna -->
    <div class="w-3/4 space-y-6 pl-10">

      <!-- Kotak ringkasan statistik jumlah perusahaan -->
      <div>
        <h3 class="mb-3 font-semibold">Server</h3>
        <div class="border-2 rounded p-6 grid grid-cols-4 text-center gap-4">

          <!-- Total semua perusahaan yang terdaftar -->
          <div>
            <p class="text-sm text-gray-400">Total Server</p>
            <p class="text-2xl font-bold mt-1">03</p>
          </div>

          <!-- Perusahaan yang berstatus aktif -->
          <div>
            <p class="text-sm text-gray-400">Active Server</p>
            <p class="text-2xl font-bold mt-1">01</p>
          </div>

          <!-- Perusahaan yang masih menunggu persetujuan -->
          <div>
            <p class="text-sm text-gray-400">Pending Server</p>
            <p class="text-2xl font-bold mt-1">01</p>
          </div>

          <!-- Perusahaan yang tidak aktif -->
          <div>
            <p class="text-sm text-gray-400">Inactive Server</p>
            <p class="text-2xl font-bold mt-1">01</p>
          </div>

        </div>
      </div>

      <!-- Tabel daftar perusahaan beserta IP dan status -->
      <div>

        <!-- Judul kolom tabel -->
        <div class="grid grid-cols-3 gap-4 mb-2 text-sm font-semibold px-1">
          <p>Server Name</p>
          <p>Server IP</p>
          <p>Agent Status</p>
        </div>

        <!-- Baris perusahaan 1: Status Aktif -->
        <div class="grid grid-cols-3 gap-4 items-center mb-3">
          <input type="text" value="PT. Central Cyber Security Office" readonly
            class="bg-transparent border rounded px-3 py-2 text-sm text-gray-300">
          <input type="text" value="12.123.234.432.567.890" readonly
            class="bg-transparent border rounded px-3 py-2 text-sm text-gray-300">
          <button class="border border-blue-400 text-blue-400 rounded py-2 text-sm w-24">
            Active
          </button>
        </div>

        <!-- Baris perusahaan 2: Status Menunggu -->
        <div class="grid grid-cols-3 gap-4 items-center mb-3">
          <input type="text" value="PT. Mirza Sejahtera Selalu" readonly
            class="bg-transparent border rounded px-3 py-2 text-sm text-gray-300">
          <input type="text" value="13.321.456.654.789.987" readonly
            class="bg-transparent border rounded px-3 py-2 text-sm text-gray-300">
          <button class="border border-yellow-400 text-yellow-400 rounded py-2 text-sm w-24">
            Pending
          </button>
        </div>

        <!-- Baris perusahaan 3: Status Tidak Aktif -->
        <div class="grid grid-cols-3 gap-4 items-center mb-3">
          <input type="text" value="PT. sadekkembangapi" readonly
            class="bg-transparent border rounded px-3 py-2 text-sm text-gray-300">
          <input type="text" value="13.321.456.654.789.980" readonly
            class="bg-transparent border rounded px-3 py-2 text-sm text-gray-300">
          <button class="border border-red-500 text-red-500 rounded py-2 text-sm w-24">
            Inactive
          </button>
        </div>

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


</body>
</html>