<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Landing Page - Central Cyber Security Office</title>
  <link rel="stylesheet" href="../css/landingpage.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes animasilp {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    html {
        animation: animasilp 300ms ease-in-out;
        scroll-behavior: smooth;
    }

    .reveal {
        opacity: 0;
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .reveal-up { transform: translateY(30px); }
    .reveal-left { transform: translateX(-40px); }
    .reveal-right { transform: translateX(40px); }

    .reveal.active {
        opacity: 1;
        transform: translate(0, 0);
    }

    /* Efek transisi halus untuk slider testimoni */
    .fade-transition {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .fade-out {
        opacity: 0;
        transform: scale(0.95);
    }
  </style>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            page: '#121318',
            surface: '#1a1b23',
            borderSubtle: '#262833',
            textMain: '#f1f3f9',
            textMuted: '#787f99',
            brand: '#6366f1',
            brandHover: '#4f46e5'
          },
          fontFamily: {
            sans: ['-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="bg-page text-textMain font-sans overflow-x-hidden antialiased">

  <header class="flex justify-between items-center px-8 py-4 bg-surface/80 backdrop-blur-md sticky top-0 z-50 border-b border-borderSubtle">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 transition-transform hover:rotate-6">
        <img src="/lp/logo.png" alt="Logo CCSO">
      </div>
      <h1 class="text-xs font-bold tracking-wider uppercase leading-tight text-textMain">Central Cyber <br><span class="text-brand">Security Office</span></h1>
    </div>

    <div class="flex gap-4">
      <a href="{{ route('login') }}"> 
        <button class="border border-borderSubtle px-5 py-2 text-xs rounded font-medium hover:bg-surface hover:border-textMuted transition-all duration-200">Login</button> 
      </a>
      <a href="#footer"> 
        <button class="bg-surface border border-borderSubtle px-5 py-2 text-xs rounded font-medium hover:bg-borderSubtle text-brand transition-all duration-200">Contact Us</button> 
      </a>
    </div>
  </header>

  <section class="reveal reveal-left relative overflow-hidden">
    <div class="bg-gradient-to-r from-surface via-[#171922] to-[#141b2e] p-16 flex flex-col md:flex-row items-center justify-between border-b border-borderSubtle gap-8">
      <div class="max-w-xl">
        <span class="text-xs font-bold uppercase tracking-widest text-brand bg-brand/10 px-3 py-1 rounded-full">Next-Gen Protection</span>
        <h2 class="text-4xl font-extrabold tracking-tight mb-4 mt-3 text-textMain leading-tight">Central Cyber <br>Security Office</h2>
        <p class="text-textMuted text-lg leading-relaxed">Unified XDR and SIEM protection for endpoints and cloud workloads. Pantau dan amankan aset digital Anda dari satu titik kendali.</p>
        <div class="flex gap-4 mt-10">
          <a href="{{ route('login') }}"> 
            <button class="bg-brand px-6 py-3 rounded text-white font-semibold hover:bg-brandHover shadow-lg hover:shadow-brand/20 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-300">Mulai Sekarang</button> 
          </a>
          <a href="#footer">
            <button class="bg-surface/50 border border-borderSubtle px-6 py-3 rounded text-textMain font-medium hover:bg-borderSubtle transition-all duration-200">Hubungi Kami</button>
          </a>
        </div>
      </div>

      <div class="w-full md:w-1/2 flex justify-center relative">
        <div class="absolute inset-0 bg-brand/5 rounded-full filter blur-3xl"></div>
        <img src="/lp/grafik.png" class="w-full max-w-lg relative z-10 animate-[pulse_4s_infinite]">
      </div>
    </div>
  </section>

  <section class="text-center px-10 py-16 reveal reveal-up">
    <h2 class="text-3xl font-bold tracking-tight mb-4 text-textMain">Dashboard One For All</h2>
    <p class="text-textMuted text-base max-w-2xl mx-auto leading-relaxed">
      CCSO menyediakan platform dasbor terpusat untuk menampilkan visualisasi analitik dan berbagai data statistik keamanan Anda secara real-time.
    </p>
  </section>

  <section class="grid md:grid-cols-3 gap-8 px-10 pb-16">
    <div class="bg-surface border border-borderSubtle p-8 rounded-xl text-center reveal reveal-left hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-all duration-300 group">
      <div class="w-14 h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur1.png" class="w-7 h-7">
      </div>
      <h4 class="text-lg font-semibold mb-2">Real-Time Monitoring</h4>
      <p class="text-textMuted text-sm leading-relaxed">Pantau dan awasi aliran data serta anomali keamanan secara instan kapan saja.</p>
    </div>

    <div class="bg-surface border border-borderSubtle p-8 rounded-xl text-center reveal reveal-up hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-all duration-300 group">
      <div class="w-14 h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur2.png" class="w-7 h-7">
      </div>
      <h4 class="text-lg font-semibold mb-2">Customizable Layout</h4>
      <p class="text-textMuted text-sm leading-relaxed">Atur tata letak komponen widget dasbor sesuai dengan preferensi analisis tim Anda.</p>
    </div>

    <div class="bg-surface border border-borderSubtle p-8 rounded-xl text-center reveal reveal-right hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-all duration-300 group">
      <div class="w-14 h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur3.png" class="w-7 h-7">
      </div>
      <h4 class="text-lg font-semibold mb-2">Efficient Analysis</h4>
      <p class="text-textMuted text-sm leading-relaxed">Proses dan bedah data ancaman siber secara cepat dengan mesin komputasi modern.</p>
    </div>
  </section>

  <section class="px-10 py-16 reveal reveal-up bg-surface/30 border-y border-borderSubtle">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-12">
      <div class="w-full md:w-1/2">
        <img src="/lp/grafik2.png" class="mx-auto max-w-md drop-shadow-[0_10px_20px_rgba(0,0,0,0.3)]">
      </div>

      <div class="w-full md:w-1/2 bg-surface border border-borderSubtle rounded-2xl p-10 shadow-xl relative">
        <div class="absolute top-0 left-0 w-2 h-full bg-brand rounded-l-2xl"></div>
        <h3 class="text-2xl font-bold mb-4 text-textMain">Tentang CCSO</h3>
        <p class="text-base text-textMuted leading-relaxed">
          Central Cyber Security Office (CCSO) menyajikan solusi pemantauan cerdas terpadu. Melalui visualisasi informatif, kami membantu perusahaan mendeteksi ancaman lebih awal sebelum berdampak pada kelangsungan bisnis.
        </p>
      </div>
    </div>
  </section>

  <section class="px-10 py-16 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-8 text-center text-textMain">Keunggulan Utama Platform</h2>

    <div class="space-y-4">
      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200 open:border-brand/40">
        <summary class="p-5 cursor-pointer font-semibold text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Open Source Security</span>
          <span class="text-brand group-open:rotate-180 transition-transform duration-200">↓</span>
        </summary>
        <p class="p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Platform kami dikembangkan di atas fondasi ekosistem terbuka yang transparan, aman, dan terus divalidasi oleh komunitas ahli siber global.
        </p>
      </details>

      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200 open:border-brand/40">
        <summary class="p-5 cursor-pointer font-semibold text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Transparansi & Fleksibilitas</span>
          <span class="text-brand group-open:rotate-180 transition-transform duration-200">↓</span>
        </summary>
        <p class="p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Anda memegang kendali penuh atas data tanpa adanya vendor lock-in, memungkinkan kustomisasi arsitektur secara fleksibel.
        </p>
      </details>

      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200 open:border-brand/40">
        <summary class="p-5 cursor-pointer font-semibold text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Dokumentasi Lengkap</span>
          <span class="text-brand group-open:rotate-180 transition-transform duration-200">↓</span>
        </summary>
        <p class="p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Akses panduan integrasi API, konfigurasi agen perlindungan, hingga playbook mitigasi insiden secara menyeluruh.
        </p>
      </details>
    </div>
  </section>

  <section class="text-center relative py-16 bg-gradient-to-b from-page to-surface border-t border-borderSubtle">
    <img src="/lp/bgcolor2.png" class="absolute inset-0 object-cover w-full h-full -z-10 opacity-10 pointer-events-none">

    <div class="px-6 max-w-5xl mx-auto">
      <h2 class="text-2xl font-bold mb-12 text-textMain">Apa Kata Pengguna Kami</h2>

      <div class="flex items-center justify-between gap-6 bg-surface border border-borderSubtle p-8 rounded-2xl shadow-xl relative">
        
        <button id="prev-btn" class="p-2 rounded-full border border-borderSubtle bg-page hover:bg-borderSubtle hover:text-brand transition-all duration-200 shrink-0">
          <img src="/lp/left.png" alt="Sebelumnya" class="w-5 h-5">
        </button>

        <div id="testimonial-container" class="flex flex-col md:flex-row items-center gap-8 text-center md:text-left mx-4 w-full fade-transition">
          <div class="flex flex-col items-center w-full md:w-44 shrink-0">
            <img id="testi-img" src="/lp/profile.png" class="w-20 h-20 rounded-full mb-3 border-2 border-brand/50 object-cover shadow-lg shadow-brand/10">
            <h4 id="testi-name" class="font-bold text-textMain tracking-wide text-base">Rian Kurniawan</h4>
            <p id="testi-email" class="text-brand text-xs mt-0.5">rian@enterprise.id</p>
          </div>

          <div class="text-sm leading-relaxed text-textMuted relative py-2">
            <span class="text-4xl text-brand/20 absolute -top-4 -left-2 font-serif">“</span>
            <p id="testi-text" class="relative z-10 italic text-gray-300">
              CCSO memberikan visibilitas penuh terhadap infrastruktur cloud kami. Dashboard One For All mempermudah tim Security Operations Center (SOC) kami dalam mendeteksi dan merespons ancaman siber dalam hitungan detik.
            </p>
          </div>
        </div>

        <button id="next-btn" class="p-2 rounded-full border border-borderSubtle bg-page hover:bg-borderSubtle hover:text-brand transition-all duration-200 shrink-0">
          <img src="/lp/right.png" alt="Selanjutnya" class="w-5 h-5">
        </button>

      </div>
    </div>
  </section>

  <footer class="bg-surface border-t border-borderSubtle px-10 py-12">
    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
      <div>
        <h3 class="mb-4 font-bold text-sm tracking-wider uppercase text-brand">Central Cyber Security Office</h3>
        <ul class="text-textMuted space-y-2.5 text-sm">
          <li class="hover:text-brand cursor-pointer transition-colors">Tentang Kami</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Fitur Utama</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Eksplorasi</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Ulasan Pengguna</li>
        </ul>
      </div>

      <div>
        <h3 class="mb-4 font-bold text-sm tracking-wider uppercase text-textMain">Layanan</h3>
        <ul class="text-textMuted space-y-2.5 text-sm">
          <li class="hover:text-brand cursor-pointer transition-colors">CCSO Cloud Secure</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Dukungan Profesional</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Layanan Konsultasi</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Pusat Pelatihan</li>
        </ul>
      </div>

      <div>
        <h3 class="mb-4 font-bold text-sm tracking-wider uppercase text-textMain">Sumber Daya</h3>
        <ul class="text-textMuted space-y-2.5 text-sm">
          <li class="hover:text-brand cursor-pointer transition-colors">Blog Keamanan</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Informasi Perusahaan</li>
          <li class="hover:text-brand cursor-pointer transition-colors">Kebijakan & Legalitas</li>
        </ul>
      </div>
    </div>

    <div class="w-full h-px bg-borderSubtle my-8 max-w-6xl mx-auto"></div>
    
    <div id="footer" class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-6 text-sm text-textMuted">
      <div class="flex items-center gap-4 flex-wrap">
        <img src="/lp/logo.png" class="h-8 transition-transform hover:scale-105">
        <p class="text-textMain">© 2026 CCSO, Inc.</p>
        <div class="h-4 w-px bg-borderSubtle hidden sm:block"></div>
        <p>Kontak Hubungi</p>
        <div class="h-4 w-px bg-borderSubtle hidden sm:block"></div>
        <div class="flex items-center gap-2 text-textMain">
          <img src="/lp/telp.png" class="h-4">
          <p>+62 1234567890</p>
        </div>
      </div>

      <div class="flex items-center gap-8 flex-wrap w-full md:w-auto justify-between md:justify-end">
        <div class="flex items-center gap-4 bg-page px-4 py-2 rounded-lg border border-borderSubtle">
          <img src="/lp/tt.png" class="h-4 cursor-pointer opacity-70 hover:opacity-100 hover:scale-110 transition-all">
          <img src="/lp/ig.png" class="h-4 cursor-pointer opacity-70 hover:opacity-100 hover:scale-110 transition-all">
          <img src="/lp/wa.png" class="h-4 cursor-pointer opacity-70 hover:opacity-100 hover:scale-110 transition-all">
          <img src="/lp/email.png" class="h-4 cursor-pointer opacity-70 hover:opacity-100 hover:scale-110 transition-all">
        </div>

        <div class="flex items-center border border-borderSubtle bg-page rounded-lg overflow-hidden focus-within:border-brand/60 transition-colors">
          <input
            type="email"
            placeholder="Berlangganan Newsletter..."
            class="bg-transparent px-4 py-2 text-xs w-48 text-textMain focus:outline-none placeholder:text-textMuted">
          <button class="bg-brand px-4 py-2 text-white text-xs font-bold hover:bg-brandHover transition-colors">Send</button>
        </div>
      </div>
    </div>
  </footer>
  
<script>
  // --- JAVASCRIPT 1: INTERACTIVE TESTIMONIAL SLIDER ---
  const dataTestimoni = [
    {
      nama: "Rian Kurniawan",
      email: "rian@enterprise.id",
      teks: "CCSO memberikan visibilitas penuh terhadap infrastruktur cloud kami. Dashboard One For All mempermudah tim Security Operations Center (SOC) kami dalam mendeteksi dan merespons ancaman siber dalam hitungan detik.",
      img: "/lp/profile.png"
    },
    {
      nama: "Sarah Amalia",
      email: "sarah.a@cyberdefense.org",
      teks: "Efisiensi analisisnya luar biasa. Pengaturan tata letak widget yang fleksibel sangat membantu analis kami menyusun dashboard taktis harian tanpa kendala teknis sedikit pun.",
      img: "/lp/profile.png" 
    },
    {
      nama: "Budi Santoso",
      email: "budi@techcorp.net",
      teks: "Solusi SIEM terbaik yang berbasis open source dengan dukungan profesional berstandar korporasi. Transparansi arsitekturnya memberikan rasa aman yang mutlak bagi manajemen.",
      img: "/lp/profile.png"
    }
  ];

  let indeksSekarang = 0;
  const wadahTesti = document.getElementById('testimonial-container');
  const teksTesti = document.getElementById('testi-text');
  const namaTesti = document.getElementById('testi-name');
  const emailTesti = document.getElementById('testi-email');
  const fotoTesti = document.getElementById('testi-img');
  const btnPrev = document.getElementById('prev-btn');
  const btnNext = document.getElementById('next-btn');

  function gantiTestimoni(indeksBaru) {
    // Jalankan efek transisi fade-out
    wadahTesti.classList.add('fade-out');
    
    setTimeout(() => {
      indeksSekarang = indeksBaru;
      
      // Mutasi konten HTML
      teksTesti.innerText = dataTestimoni[indeksSekarang].teks;
      namaTesti.innerText = dataTestimoni[indeksSekarang].nama;
      emailTesti.innerText = dataTestimoni[indeksSekarang].email;
      fotoTesti.src = dataTestimoni[indeksSekarang].img;
      
      // Kembalikan efek fade-in
      wadahTesti.classList.remove('fade-out');
    }, 300);
  }

  btnPrev.addEventListener('click', () => {
    let indeks = indeksSekarang === 0 ? dataTestimoni.length - 1 : indeksSekarang - 1;
    gantiTestimoni(indeks);
  });

  btnNext.addEventListener('click', () => {
    let indeks = indeksSekarang === dataTestimoni.length - 1 ? 0 : indeksSekarang + 1;
    gantiTestimoni(indeks);
  });


  // --- JAVASCRIPT 2: SCROLL REVEAL ANIMATION ---
  const reveals = document.querySelectorAll('.reveal');

  function revealOnScroll() {
    const windowHeight = window.innerHeight;

    reveals.forEach((el, index) => {
      const elementTop = el.getBoundingClientRect().top;
      const elementBottom = el.getBoundingClientRect().bottom;

      if (elementTop < windowHeight - 80 && elementBottom > 0) {
        setTimeout(() => {
          el.classList.add('active');
        }, index * 60);
      } else {
        el.classList.remove('active');
      }
    });
  }

  window.addEventListener('scroll', revealOnScroll);
  // Jalankan sekali saat inisialisasi awal halaman
  revealOnScroll();
</script>

</body>
</html>