<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Overview</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/js/app.js')
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('Customer.components.header')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="flex items-center py-3 border-b-2">

    <!-- Foto profil yang menggantung ke bawah -->
    <img class="w-48 h-48 mt-40 ml-14 absolute" src="/profilee/profile.png">

    <!-- Menu tab: Overview, Profile Settings, Company, Customization Dashboard -->
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
  <div class="flex p-6 gap-6 flex-1 ">

    <!-- KOLOM KIRI: Ringkasan singkat profil pengguna -->
    <div class="mt-32">
      <div class="p-6 rounded">

        <!-- Nama dan nomor telepon pengguna -->
        <h2 class="text-left mt-2 font-semibold">User Tester ABCD</h2>
        <p class="text-left text-sm text-gray-400">+62888888888</p>

        <!-- Daftar perusahaan yang diikuti pengguna -->
        <div class="mt-10 text-sm text-gray-400">
          <p>Total Server: 3</p>
          <p>Total Dashboard: 3</p>
        </div>

        <!-- Tanggal bergabung pengguna -->
        <p class="mt-6 text-sm text-gray-500">Join Date: 01-10-2025</p>

      </div>
    </div>


    <!-- KOLOM KANAN: Detail informasi profil -->
    <div class="w-2/3 space-y-6 pl-10">

      <!-- Baris atas: Detail profil dan perusahaan berdampingan -->
      <div class="flex gap-6">

        <!-- Informasi detail akun pengguna -->
        <div class="w-1/2">
          <h3 class="mb-6">Profile</h3>
          <div class="border-2 p-4 rounded text-sm">
            <p>User Tester ABCD</p>
            <p class="text-gray-400">testerabcd@gmail.com</p>
            <p class="text-gray-400">+62 12345678</p>
          </div>
        </div>

        <!-- Daftar perusahaan yang terdaftar pada akun -->
        <div class="w-1/2">
          <h3 class="mb-6">Agent</h3>
          <div class="border-2 p-4 rounded text-sm text-gray-400">
            <p>Agent 123</p>
            <p>Agent 456</p>
            <p>Agent 789</p>
          </div>
        </div>

      </div>

      <!-- Baris bawah: Diagram status alert keamanan -->
      <div>
        <h3 class="mb-2">Alert Status</h3>
        <div class="border-2 h-40 flex items-center justify-center text-gray-400">
          GAMBAR DIAGRAM ALERT
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

      <!-- Form kirim email ke tim CCSO -->
      <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
        <input type="email" placeholder="Sent to our Email..." class="bg-transparent px-3 py-1 text-sm w-54 text-gray-800">
        <button class="bg-blue-500 px-3 py-1 text-white text-sm hover:bg-blue-600">›</button>
      </div>

    </div>
  </footer>

</body>
</html>
