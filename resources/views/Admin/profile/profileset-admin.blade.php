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
        <a href="{{ route('profile-setting-admin') }}" class="py-3 py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">Profile Settings</a>
        <a href="{{ route('profile-agent') }}" class="py-3 text-gray-400 text-sm hover:text-white transition">Agent</a>
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
  <div class="flex p-8 gap-6 flex-1">

    <!-- KOLOM KIRI: Ringkasan singkat profil pengguna -->
    <div class="mt-32">
      <div class="p-6 rounded">

        <!-- Nama dan nomor telepon pengguna -->
        <h2 class="mt-2 font-semibold">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-400">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>


<!-- KOLOM KANAN: Form pengaturan profil -->
  <form action="{{ route('profile.update') }}" method="POST" class="w-2/3 space-y-6 pl-10">
    @csrf

    <!-- SUCCESS -->
    @if(session('success'))
      <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded">
        {{ session('success') }}
      </div>
    @endif

    <!-- ERROR -->
    @if(session('error'))
      <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded">
        {{ session('error') }}
      </div>
    @endif


    <!-- FULL NAME -->
    <div class="flex flex-col gap-2">

      <label class="text-sm font-semibold">
        Full Name
      </label>

      <input type="text" name="name" value="{{ auth()->user()->name }}" class="bg-transparent
                     border border-white
                     rounded
                     px-4 py-2
                     text-sm text-gray-300
                     focus:outline-none
                     focus:border-blue-400">

    </div>


    <!-- EMAIL -->
    <div class="flex flex-col gap-2">

      <label class="text-sm font-semibold">
        Email
      </label>

      <input type="email" name="email" value="{{ auth()->user()->email }}" class="bg-transparent
                     border border-white
                     rounded
                     px-4 py-2
                     text-sm text-gray-300
                     focus:outline-none
                     focus:border-blue-400">

    </div>


    <!-- PHONE -->
    <div class="flex flex-col gap-2">

      <label class="text-sm font-semibold">
        Personal Phone Number
      </label>

      <input type="text" name="no_telp" value="{{ auth()->user()->no_telp }}" class="bg-transparent
                     border border-white
                     rounded
                     px-4 py-2
                     text-sm text-gray-300
                     focus:outline-none
                     focus:border-blue-400">

    </div>


    <!-- CHANGE PASSWORD -->
    <div class="flex flex-col gap-4">

      <label class="text-sm font-semibold">
        Change Password
      </label>

      <!-- CURRENT PASSWORD -->
      <input type="password" name="current_password" placeholder="Current Password" class="bg-transparent
                     border border-white
                     rounded
                     px-4 py-2
                     text-sm text-gray-300
                     focus:outline-none
                     focus:border-blue-400">

      <!-- NEW PASSWORD -->
      <input type="password" name="new_password" placeholder="New Password" class="bg-transparent
                     border border-white
                     rounded
                     px-4 py-2
                     text-sm text-gray-300
                     focus:outline-none
                     focus:border-blue-400">

      <!-- CONFIRM PASSWORD -->
      <input type="password" name="new_password_confirmation" placeholder="Confirm New Password" class="bg-transparent
                     border border-white
                     rounded
                     px-4 py-2
                     text-sm text-gray-300
                     focus:outline-none
                     focus:border-blue-400">

    </div>


    <!-- SAVE BUTTON -->
    <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 transition text-white px-5 py-2 rounded font-medium">
      Save Changes
    </button>

  </form>
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