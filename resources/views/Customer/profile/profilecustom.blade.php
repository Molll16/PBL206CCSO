<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customization Dashboard</title>
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


    <!-- KOLOM KANAN: Halaman kustomisasi dashboard pengguna -->
    <div class="w-2/3 space-y-6 pl-10">

      <!-- Kotak ringkasan statistik jumlah dashboard -->
      <div>
        <h3 class="mb-3 font-semibold">Customization</h3>
        <div class="border-2 rounded p-6 grid grid-cols-3 text-center gap-4">

          <!-- Total semua dashboard yang dimiliki -->
          <div>
            <p class="text-sm text-gray-400">Total Dashboard</p>
            <p class="text-2xl font-bold mt-1">03</p>
          </div>

          <!-- Dashboard yang sedang aktif -->
          <div>
            <p class="text-sm text-gray-400">Active Dashboard</p>
            <p class="text-2xl font-bold mt-1">03</p>
          </div>

          <!-- Dashboard yang sedang dipakai saat ini -->
          <div>
            <p class="text-sm text-gray-400">Dashboard In Use</p>
            <p class="text-2xl font-bold mt-1">ID: 01</p>
          </div>

        </div>
      </div>

      <!-- Tabel daftar dashboard milik pengguna -->
      <div>
        <h3 class="mb-3 font-semibold">Your Dashboard</h3>

        <div class="border-2 rounded overflow-hidden">

          <!-- Judul kolom tabel: ukuran HARUS sama dengan konstanta KOLOM di JS -->
          <div class="grid text-sm font-semibold border-b-2"
               style="grid-template-columns: 60px 1fr 180px 120px 100px;">
            <p class="px-4 py-3 border-r-2 text-center">ID</p>
            <p class="px-4 py-3 border-r-2 text-center">Dashboard Name</p>
            <p class="px-4 py-3 border-r-2 text-center">Last Modified</p>
            <p class="px-4 py-3 border-r-2 text-center">Action</p>
            <p class="px-4 py-3 text-center">Status</p>
          </div>

          <!-- Baris dashboard: dirender oleh JavaScript -->
          <div id="tabelDashboard"></div>

        </div>

        <!-- Tombol untuk menambah dashboard baru -->
        <button
          onclick="tambahDashboard()"
          class="mt-4 border-2 border-white bg-[#3282B8] hover:bg-[#2f73a0] text-white text-sm px-6 py-2 rounded">
          Add New Customization
        </button>

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
      if (status === 'Active') return 'text-blue-400'
      return 'text-gray-400'
    }

    // Fungsi untuk menampilkan ulang seluruh tabel dashboard
    function renderTabel() {
      const container = document.getElementById('tabelDashboard')
      container.innerHTML = ''

      daftarDashboard.forEach((item, index) => {
        const baris = document.createElement('div')
        baris.className = 'grid items-center text-sm border-b-2 border-gray-600 last:border-b-0'
        baris.style.gridTemplateColumns = KOLOM
        baris.innerHTML = `
          <!-- Nomor ID dashboard -->
          <p class="px-4 py-3 border-r-2 border-gray-600 text-center text-gray-300">${item.id}</p>

          <!-- Nama dashboard, berubah jadi input saat tombol edit ditekan -->
          <p class="px-4 py-3 border-r-2 border-gray-600 text-gray-300" id="nama-${index}">${item.nama}</p>

          <!-- Tanggal terakhir dashboard diubah -->
          <p class="px-4 py-3 border-r-2 border-gray-600 text-center text-gray-400 text-xs">${item.tanggal}</p>

          <!-- Tombol aksi: hapus baris dan edit nama -->
          <div class="px-4 py-3 border-r-2 border-gray-600 flex items-center justify-center gap-3">
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
          class="bg-transparent border border-blue-400 rounded px-2 py-1 text-sm text-gray-200 focus:outline-none w-full"
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