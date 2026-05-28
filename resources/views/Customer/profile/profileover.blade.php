<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Overview</title>
  <script src="https://cdn.tailwindcss.com"></script>

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

  <style>
    body { background-color: #121318; }

    /* Accent bar */
    .accent-bar {
      background: #6366f1;
      box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
    }

    /* Fade in */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.35s ease both; }

    /* Border tab bottom */
    .tab-border { border-bottom: 2px solid #262833; }

    /* Profile card */
    .profile-card {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Content card */
    .content-card {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Alert box */
    .alert-box {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Footer */
    .footer-bar {
      background: #0e0f13;
      border-top: 1px solid #262833;
    }

    /* Logout button */
    .btn-logout {
      border: 1px solid #262833;
      background: transparent;
      transition: background 0.2s;
    }
    .btn-logout:hover { background: #1a1b23; }
  </style>
</head>
<body class="text-textMain font-sans flex flex-col min-h-screen bg-page antialiased">

@include('Customer.components.header')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="flex items-center py-3 tab-border">

    <!-- Foto profil yang menggantung ke bawah -->
    <img class="w-48 h-48 mt-40 ml-14 absolute" src="/profilee/profile.png">

    <!-- Menu tab: Overview, Profile Settings, Company, Customization Dashboard -->
    <div class="flex items-center gap-10 ml-[330px]">
      <a href={{ Route('profile-overview') }} class="text-sm cursor-pointer text-brand">Overview</a>
      <a href={{ Route('profile-setting') }}  class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Profile Settings</a>
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
        <h2 class="text-left mt-2 font-semibold text-textMain">User Tester ABCD</h2>
        <p class="text-left text-sm text-textMuted">+62888888888</p>

        <!-- Daftar perusahaan yang diikuti pengguna -->
        <div class="mt-10 text-sm text-textMuted">
          <p>Total Server: 3</p>
          <p>Total Dashboard: 3</p>
        </div>

        <!-- Tanggal bergabung pengguna -->
        <p class="mt-6 text-sm" style="color:#4a4f66">Join Date: 01-10-2025</p>

      </div>
    </div>


    <!-- KOLOM KANAN: Detail informasi profil -->
    <div class="w-2/3 space-y-6 pl-10">

      <!-- Baris atas: Detail profil dan perusahaan berdampingan -->
      <div class="flex gap-6 animate-fade-in">

        <!-- Informasi detail akun pengguna -->
        <div class="w-1/2">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-[3px] h-5 rounded-full accent-bar"></div>
            <h3 class="font-semibold text-textMain tracking-wide">Profile</h3>
          </div>
          <div class="content-card p-4 rounded-xl text-sm">
            <p class="text-textMain">User Tester ABCD</p>
            <p class="text-textMuted">testerabcd@gmail.com</p>
            <p class="text-textMuted">+62 12345678</p>
          </div>
        </div>

        <!-- Daftar perusahaan yang terdaftar pada akun -->
        <div class="w-1/2">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-[3px] h-5 rounded-full accent-bar"></div>
            <h3 class="font-semibold text-textMain tracking-wide">Agent</h3>
          </div>
          <div class="content-card p-4 rounded-xl text-sm text-textMuted">
            <p>Agent 123</p>
            <p>Agent 456</p>
            <p>Agent 789</p>
          </div>
        </div>

      </div>

      <!-- Baris bawah: Diagram status alert keamanan -->
      <div class="animate-fade-in">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Alert Status</h3>
        </div>
        <div class="alert-box rounded-xl h-40 flex items-center justify-center text-textMuted">
          GAMBAR DIAGRAM ALERT
        </div>
      </div>

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

      <!-- Form kirim email ke tim CCSO -->
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

</body>
</html>