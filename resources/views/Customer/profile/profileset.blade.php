<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/css/customer/profile/profileset.css')
  @vite('resources/js/app.js')

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            page:         '#121318',
            surface:      '#1a1b23',
            borderSubtle: '#262833',
            textMain:     '#f1f3f9',
            textMuted:    '#787f99',
            brand:        '#6366f1',
            brandHover:   '#4f46e5'
          }
        }
      }
    }
  </script>

</head>
<body class="text-textMain font-sans flex flex-col min-h-screen bg-page antialiased">

@include('Customer.components.header')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="flex items-center py-3 tab-border">

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
      <a href={{ Route('profile-setting') }}  class="text-sm cursor-pointer text-brand">Profile Settings</a>
      <a href={{ Route('profile-server') }}   class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Server</a>
      <a href={{ Route('profile-custom') }}   class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Customization Dashboard</a>
    </div>



  </div>


  <!-- ===== KONTEN UTAMA ===== -->
  <div class="flex p-6 gap-6 flex-1">

    <!-- KOLOM KIRI: Ringkasan singkat profil pengguna -->
    <div class="mt-32">
      <div class="profile-card p-6 mx-8 mt-6 rounded-xl">

        <!-- Nama dan nomor telepon pengguna -->
        <h2 class="mt-2 font-semibold text-textMain">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-textMuted">{{ auth()->user()->no_telp }}</p>

      </div>
    </div>


    <!-- KOLOM KANAN: Form pengaturan profil -->
    <div class="w-2/3 pl-10 space-y-8 animate-fade-in">

        <div class="flex flex-col gap-2">
          <label class="text-sm font-semibold text-textMain">Full Name</label>
          <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-input">
        </div>

        <div class="flex flex-col gap-2">
          <label class="text-sm font-semibold text-textMain">Email</label>
          <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-input">
        </div>

        <div class="flex flex-col gap-2">
          <label class="text-sm font-semibold text-textMain">Personal Phone Number</label>
          <input type="text" name="no_telp" value="{{ auth()->user()->no_telp }}" class="form-input">
        </div>

      <hr class="divider my-6">

      <form action="{{ route('changepw.update') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Section heading -->
        <div class="flex items-center gap-3 mb-1">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <label class="text-sm font-semibold text-textMain tracking-wide">Change Password</label>
        </div>

        @if(session('success') && session('password_success'))
          <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
            {{ session('error') }}
          </div>
        @endif

        @if($errors->any())
          <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
          </div>
        @endif

        <input type="password" name="current_password" placeholder="Current Password" required class="form-input">
        <input type="password" name="new_password" placeholder="New Password" required class="form-input">
        <input type="password" name="new_password_confirmation" placeholder="Confirm New Password" required class="form-input">

        <button type="submit" class="btn-save">
          Update Password
        </button>
      </form>

    </div>

  </div>


  <!-- ===== FOOTER ===== -->
  <footer class="footer-bar px-10 py-4">
    <div class="mt-2 px-1 flex flex-wrap items-center gap-4 text-sm text-textMuted">

      <!-- Logo footer -->
      <img src="/lp/logo.png" class="h-10">

      <!-- Hak cipta -->
      <p class="text-textMain">© 2026 CCSO, Inc.</p>
      <img src="/lp/garis.png" class="h-5">

      <!-- Tautan hubungi kami -->
      <p class="text-textMain">Contact Us</p>
      <img src="/lp/garis.png" class="h-5">

      <!-- Nomor telepon kantor -->
      <div class="flex items-center gap-2">
        <img src="/lp/telp.png" class="h-5">
        <p>+62 1234567890</p>
      </div>

      <!-- Ikon media sosial -->
      <div class="flex items-center gap-6 ml-2">
        <img src="/lp/tt.png"    class="h-5 cursor-pointer">
        <img src="/lp/ig.png"    class="h-5 cursor-pointer">
        <img src="/lp/wa.png"    class="h-5 cursor-pointer">
        <img src="/lp/email.png" class="h-5 cursor-pointer">
      </div>

      <!-- Form kirim pesan ke email tim CCSO -->
      <div class="flex items-center ml-auto overflow-hidden rounded-lg"
           style="border: 1px solid #262833; background: #1a1b23;">
        <input type="email" placeholder="Sent to our Email..."
               class="px-3 py-1 text-sm w-54 bg-transparent text-textMain placeholder-textMuted focus:outline-none">
        <button class="px-3 py-1 text-white text-sm transition-colors"
                style="background:#6366f1"
                onmouseover="this.style.background='#4f46e5'"
                onmouseout="this.style.background='#6366f1'">›</button>
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