<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Overview</title>
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

    /* Stat card */
    .stat-card {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Profile card */
    .profile-card {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Input readonly */
    input.server-input {
      background: transparent;
      border: 1px solid #262833;
      color: #f1f3f9;
      border-radius: 6px;
      padding: 8px 12px;
      font-size: 0.875rem;
      width: 100%;
    }
    input.server-input:focus { outline: none; }

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

    <!-- Menu tab halaman profil -->
    <div class="flex items-center gap-10 ml-[330px]">
      <a href={{ Route('profile-overview') }} class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Overview</a>
      <a href={{ Route('profile-setting') }}  class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Profile Settings</a>
      <a href={{ Route('profile-server') }}   class="text-sm cursor-pointer text-brand">Server</a>
      <a href={{ Route('profile-custom') }}   class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Customization Dashboard</a>
    </div>


  </div>


  <!-- ===== KONTEN UTAMA ===== -->
  <div class="flex p-6 gap-6 flex-1">

    <!-- KOLOM KIRI: Ringkasan singkat profil pengguna -->
    <div class="mt-32">
      <div class="profile-card p-6 mx-8 mt-6 rounded-xl">

        <!-- Nama dan nomor telepon pengguna -->
        <h2 class="mt-2 font-semibold text-textMain">User Tester ABCD</h2>
        <p class="text-sm text-textMuted">+62888888888</p>

        <!-- Daftar perusahaan yang diikuti pengguna -->
        <div class="mt-10 text-sm text-textMuted">
          <p>Total Server: 3</p>
          <p>Total Dashboard: 3</p>
        </div>

        <!-- Tanggal bergabung pengguna -->
        <p class="mt-6 text-sm" style="color:#4a4f66">Join Date: 01-10-2025</p>

      </div>
    </div>


    <!-- KOLOM KANAN: Data perusahaan pengguna -->
    <div class="w-3/4 space-y-6 pl-10">

      <!-- Kotak ringkasan statistik jumlah perusahaan -->
      <div class="animate-fade-in">

        <!-- Section heading with accent bar -->
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Server</h3>
        </div>

        <div class="stat-card rounded-xl p-6 grid grid-cols-4 text-center gap-4">

          <!-- Total semua perusahaan yang terdaftar -->
          <div>
            <p class="text-sm text-textMuted">Total Server</p>
            <p class="text-2xl font-bold mt-1 text-textMain">03</p>
          </div>

          <!-- Perusahaan yang berstatus aktif -->
          <div>
            <p class="text-sm text-textMuted">Active Server</p>
            <p class="text-2xl font-bold mt-1 text-textMain">01</p>
          </div>

          <!-- Perusahaan yang masih menunggu persetujuan -->
          <div>
            <p class="text-sm text-textMuted">Pending Server</p>
            <p class="text-2xl font-bold mt-1 text-textMain">01</p>
          </div>

          <!-- Perusahaan yang tidak aktif -->
          <div>
            <p class="text-sm text-textMuted">Inactive Server</p>
            <p class="text-2xl font-bold mt-1 text-textMain">01</p>
          </div>

        </div>
      </div>

      <!-- Tabel daftar perusahaan beserta IP dan status -->
      <div class="animate-fade-in">

        <!-- Judul kolom tabel -->
        <div class="grid grid-cols-3 gap-4 mb-2 text-xs font-semibold px-1 text-textMuted tracking-wider uppercase">
          <p>Server Name</p>
          <p>Server IP</p>
          <p>Agent Status</p>
        </div>

        <!-- Baris perusahaan 1: Status Aktif -->
        <div class="grid grid-cols-3 gap-4 items-center mb-3">
          <input type="text" value="PT. Central Cyber Security Office" readonly class="server-input">
          <input type="text" value="12.123.234.432.567.890" readonly class="server-input">
          <button class="border border-blue-400 text-blue-400 rounded-lg py-2 text-sm w-24">
            Active
          </button>
        </div>

        <!-- Baris perusahaan 2: Status Menunggu -->
        <div class="grid grid-cols-3 gap-4 items-center mb-3">
          <input type="text" value="PT. Mirza Sejahtera Selalu" readonly class="server-input">
          <input type="text" value="13.321.456.654.789.987" readonly class="server-input">
          <button class="border border-yellow-400 text-yellow-400 rounded-lg py-2 text-sm w-24">
            Pending
          </button>
        </div>

        <!-- Baris perusahaan 3: Status Tidak Aktif -->
        <div class="grid grid-cols-3 gap-4 items-center mb-3">
          <input type="text" value="PT. sadekkembangapi" readonly class="server-input">
          <input type="text" value="13.321.456.654.789.980" readonly class="server-input">
          <button class="border border-red-500 text-red-500 rounded-lg py-2 text-sm w-24">
            Inactive
          </button>
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


</body>
</html>