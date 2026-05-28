<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customization Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/js/app.js')

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            page:        '#121318',
            surface:     '#1a1b23',
            borderSubtle:'#262833',
            textMain:    '#f1f3f9',
            textMuted:   '#787f99',
            brand:       '#6366f1',
            brandHover:  '#4f46e5'
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

    /* Pulse dot */
    .pulse-dot {
      background: #6366f1;
      box-shadow: 0 0 6px rgba(99, 102, 241, 0.9);
    }

    /* Fade in */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.35s ease both; }

    /* Hover scale */
    .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.35); }

    /* Border tab bottom */
    .tab-border { border-bottom: 2px solid #262833; }

    /* Table rows */
    .table-header {
      background: #1a1b23;
      border-bottom: 1px solid #262833;
    }
    .table-row {
      border-bottom: 1px solid #262833;
    }
    .table-row:last-child { border-bottom: none; }
    .table-cell-border { border-right: 1px solid #262833; }

    /* Stat card */
    .stat-card {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Dashboard table wrapper */
    .table-wrapper {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Profile card */
    .profile-card {
      background: #1a1b23;
      border: 1px solid #262833;
    }

    /* Footer */
    .footer-bar {
      background: #0e0f13;
      border-top: 1px solid #262833;
    }

    /* Add button */
    .btn-add {
      background: #6366f1;
      border: 1px solid rgba(99,102,241,0.4);
      box-shadow: 0 0 14px rgba(99,102,241,0.3);
      transition: background 0.2s;
    }
    .btn-add:hover { background: #4f46e5; }

    /* Logout button */
    .btn-logout {
      border: 1px solid #262833;
      background: transparent;
      transition: background 0.2s;
    }
    .btn-logout:hover { background: #1a1b23; }

    /* Inline edit input */
    input.inline-edit {
      background: transparent;
      border: 1px solid #6366f1;
      border-radius: 4px;
      color: #f1f3f9;
    }
    input.inline-edit:focus { outline: none; }
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
      <a href={{ Route('profile-overview') }}  class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Overview</a>
      <a href={{ Route('profile-setting') }}   class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Profile Settings</a>
      <a href={{ Route('profile-server') }}    class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Server</a>
      <a href={{ Route('profile-custom') }}    class="text-sm cursor-pointer text-brand">Customization Dashboard</a>
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


    <!-- KOLOM KANAN: Halaman kustomisasi dashboard pengguna -->
    <div class="w-2/3 space-y-6 pl-10">

      <!-- Kotak ringkasan statistik jumlah dashboard -->
      <div class="animate-fade-in">

        <!-- Section heading with accent bar -->
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Customization</h3>
        </div>

        <div class="stat-card rounded-xl p-6 grid grid-cols-3 text-center gap-4">

          <!-- Total semua dashboard yang dimiliki -->
          <div>
            <p class="text-sm text-textMuted">Total Dashboard</p>
            <p class="text-2xl font-bold mt-1 text-textMain">03</p>
          </div>

          <!-- Dashboard yang sedang aktif -->
          <div>
            <p class="text-sm text-textMuted">Active Dashboard</p>
            <p class="text-2xl font-bold mt-1 text-textMain">03</p>
          </div>

          <!-- Dashboard yang sedang dipakai saat ini -->
          <div>
            <p class="text-sm text-textMuted">Dashboard In Use</p>
            <p class="text-2xl font-bold mt-1 text-brand">ID: 01</p>
          </div>

        </div>
      </div>

      <!-- Tabel daftar dashboard milik pengguna -->
      <div class="animate-fade-in">

        <!-- Section heading with accent bar -->
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Your Dashboard</h3>
        </div>

        <div class="table-wrapper rounded-xl overflow-hidden">

          <!-- Judul kolom tabel: ukuran HARUS sama dengan konstanta KOLOM di JS -->
          <div class="grid text-sm font-semibold table-header"
               style="grid-template-columns: 60px 1fr 180px 120px 100px;">
            <p class="px-4 py-3 table-cell-border text-center text-textMuted tracking-wider uppercase text-xs">ID</p>
            <p class="px-4 py-3 table-cell-border text-center text-textMuted tracking-wider uppercase text-xs">Dashboard Name</p>
            <p class="px-4 py-3 table-cell-border text-center text-textMuted tracking-wider uppercase text-xs">Last Modified</p>
            <p class="px-4 py-3 table-cell-border text-center text-textMuted tracking-wider uppercase text-xs">Action</p>
            <p class="px-4 py-3 text-center text-textMuted tracking-wider uppercase text-xs">Status</p>
          </div>

          <!-- Baris dashboard: dirender oleh JavaScript -->
          <div id="tabelDashboard"></div>

        </div>

        <!-- Tombol untuk menambah dashboard baru -->
        <button
          onclick="tambahDashboard()"
          class="mt-4 btn-add text-white text-sm px-6 py-2 rounded-lg">
          Add New Customization
        </button>

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


  <!-- ===== JAVASCRIPT ===== -->
  <script>

    // Ukuran kolom tabel: harus sama persis antara header HTML di atas dan setiap baris di sini
    const KOLOM = '60px 1fr 180px 120px 100px'

    // Data awal dashboard pengguna
    let daftarDashboard = [
      { id: '01', nama: 'Custom No 1',          tanggal: '09-10-25 02:28:05', status: 'In Use' },
      { id: '02', nama: 'Tester dashboard two', tanggal: '09-10-25 09:26:21', status: 'Active' },
      { id: '03', nama: 'Random',               tanggal: '11-10-25 21:02:47', status: 'Active' },
    ]

    // Fungsi untuk menentukan warna teks berdasarkan status dashboard
    function warnaStatus(status) {
      if (status === 'In Use') return 'text-green-400'
      if (status === 'Active') return 'text-brand'
      return 'text-textMuted'
    }

    // Fungsi untuk menampilkan ulang seluruh tabel dashboard
    function renderTabel() {
      const container = document.getElementById('tabelDashboard')
      container.innerHTML = ''

      daftarDashboard.forEach((item, index) => {
        const baris = document.createElement('div')
        baris.className = 'grid items-center text-sm table-row hover:bg-[#1e1f29] transition-colors'
        baris.style.gridTemplateColumns = KOLOM
        baris.innerHTML = `
          <!-- Nomor ID dashboard -->
          <p class="px-4 py-3 table-cell-border text-center" style="color:#787f99">${item.id}</p>

          <!-- Nama dashboard, berubah jadi input saat tombol edit ditekan -->
          <p class="px-4 py-3 table-cell-border" style="color:#f1f3f9" id="nama-${index}">${item.nama}</p>

          <!-- Tanggal terakhir dashboard diubah -->
          <p class="px-4 py-3 table-cell-border text-center text-xs" style="color:#787f99">${item.tanggal}</p>

          <!-- Tombol aksi: hapus baris dan edit nama -->
          <div class="px-4 py-3 table-cell-border flex items-center justify-center gap-3">
            <button onclick="hapusDashboard(${index})" class="hover:opacity-70">
              <img src="/profilee/hapus.png">
            </button>
            <button onclick="editDashboard(${index})" class="hover:opacity-70">
              <img src="/profilee/edit.png">
            </button>
          </div>

          <!-- Status dashboard dengan warna berbeda sesuai kondisi -->
          <p class="px-4 py-3 text-center font-semibold ${warnaStatus(item.status)}">${item.status}</p>
        `
        container.appendChild(baris)
      })
    }

    // Fungsi untuk menghapus satu baris dashboard berdasarkan posisinya
    function hapusDashboard(index) {
      daftarDashboard.splice(index, 1)
      renderTabel()
    }

    // Fungsi untuk mengubah nama dashboard menjadi input yang bisa diketik
    function editDashboard(index) {
      const elNama = document.getElementById(`nama-${index}`)

      // Jika sudah dalam mode edit, langsung simpan
      if (elNama.tagName === 'INPUT') {
        simpanEdit(index, elNama.value)
        return
      }

      // Ganti teks nama menjadi kotak input agar bisa diedit
      const nilaiLama = daftarDashboard[index].nama
      elNama.outerHTML = `
        <input
          id="nama-${index}"
          type="text"
          value="${nilaiLama}"
          onblur="simpanEdit(${index}, this.value)"
          onkeydown="if(event.key==='Enter') simpanEdit(${index}, this.value)"
          class="inline-edit px-2 py-1 text-sm focus:outline-none w-full"
        >
      `

      // Fokus otomatis ke input agar langsung bisa diketik
      setTimeout(() => document.getElementById(`nama-${index}`)?.focus(), 50)
    }

    // Fungsi untuk menyimpan hasil edit dan kembali ke tampilan teks biasa
    function simpanEdit(index, nilaiBaru) {
      daftarDashboard[index].nama = nilaiBaru.trim() || daftarDashboard[index].nama
      renderTabel()
    }

    // Fungsi untuk menambah dashboard baru dengan ID dan waktu otomatis
    function tambahDashboard() {
      const idBaru = String(daftarDashboard.length + 1).padStart(2, '0')
      const s = new Date()
      const tanggal = `${String(s.getDate()).padStart(2,'0')}-${String(s.getMonth()+1).padStart(2,'0')}-${String(s.getFullYear()).slice(2)} ${String(s.getHours()).padStart(2,'0')}:${String(s.getMinutes()).padStart(2,'0')}:${String(s.getSeconds()).padStart(2,'0')}`
      daftarDashboard.push({ id: idBaru, nama: 'New Dashboard', tanggal, status: 'Active' })
      renderTabel()
    }

    // Tampilkan tabel saat halaman pertama kali dibuka
    renderTabel()
  </script>


</body>
</html>