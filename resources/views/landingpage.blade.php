<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CCSO</title>
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
    .feat-card {
        opacity: 0;
        transform: translateY(40px) scale(0.85);
        will-change: transform, opacity;
    }

    /* ── Desktop Carousel ── */
    .carousel-wrap {
      position: relative;
      width: 340px;
      height: 280px;
      flex-shrink: 0;
      align-items: center;
      justify-content: center;
      display: none;
    }
    @media (min-width: 1024px) {
      .carousel-wrap { display: flex; }
    }
    .carousel-track {
      position: relative;
      width: 100%;
      height: 100%;
    }
    .c-card {
      position: absolute;
      width: 190px;
      height: 240px;
      border-radius: 18px;
      background: #1a1b23;
      border: 1px solid #262833;
      overflow: hidden;
      transition: transform 0.4s cubic-bezier(0.4,0,0.2,1),
                  opacity 0.4s ease,
                  filter 0.4s ease;
      top: 50%; left: 50%;
      margin-left: -95px; margin-top: -120px;
      will-change: transform, opacity, filter;
    }
    .c-card[data-pos="center"]       { transform: translateX(0) scale(1);       opacity: 1;    filter: blur(0) brightness(1);    z-index: 10; border-color: rgba(99,102,241,.35); }
    .c-card[data-pos="left"]         { transform: translateX(-145px) scale(.78); opacity: .45;  filter: blur(2px) brightness(.6); z-index: 5; }
    .c-card[data-pos="right"]        { transform: translateX(145px) scale(.78);  opacity: .45;  filter: blur(2px) brightness(.6); z-index: 5; }
    .c-card[data-pos="hidden-left"]  { transform: translateX(-280px) scale(.6);  opacity: 0;    filter: blur(4px);                z-index: 1; }
    .c-card[data-pos="hidden-right"] { transform: translateX(280px) scale(.6);   opacity: 0;    filter: blur(4px);                z-index: 1; }
    .c-card-inner {
      width: 100%; height: 100%;
      padding: 20px;
      display: flex; flex-direction: column;
    }

    /* ── Mobile horizontal card scroll ── */
    .mobile-cards-scroll {
      display: flex;
      overflow-x: auto;
      gap: 12px;
      padding: 4px 20px 16px;
      scrollbar-width: none;
      -webkit-overflow-scrolling: touch;
    }
    .mobile-cards-scroll::-webkit-scrollbar { display: none; }
    @media (min-width: 1024px) {
      .mobile-cards-scroll { display: none; }
    }
    .m-card {
      flex-shrink: 0;
      width: 155px;
      height: 195px;
      border-radius: 16px;
      background: #1a1b23;
      border: 1px solid #262833;
      padding: 16px;
      display: flex;
      flex-direction: column;
    }
    .m-card-label {
      font-size: 9px; font-weight: 700;
      letter-spacing: .08em; text-transform: uppercase;
      margin-bottom: 10px;
    }
    .m-card-icon {
      width: 32px; height: 32px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 10px;
    }
    .m-card-title { font-size: 14px; font-weight: 800; color: #f1f3f9; margin-top: auto; }
    .m-card-desc  { font-size: 10px; color: #787f99; margin-top: 4px; line-height: 1.4; }

    /* ── Pulse dot ── */
    @keyframes pulse-dot {
      0%,100% { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
      50%      { box-shadow: 0 0 0 5px rgba(34,197,94,0); }
    }
    .pulse-dot-anim { animation: pulse-dot 1.5s ease-in-out infinite; }

    /* ── FAQ arrow ── */
    details[open] .faq-arrow { transform: rotate(180deg); }
    details[open] { border-color: rgba(99,102,241,.4) !important; }
    .faq-arrow { transition: transform .2s; }
  </style>

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
          },
          fontFamily: {
            sans: ['-apple-system','BlinkMacSystemFont','"Segoe UI"','Roboto','Helvetica','Arial','sans-serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="bg-page text-textMain font-sans overflow-x-hidden antialiased">

  <!-- ══════════════════════════════════════════
       HEADER
  ══════════════════════════════════════════ -->
  <header class="flex justify-between items-center px-4 sm:px-8 py-3 sm:py-4 bg-surface/80 backdrop-blur-md sticky top-0 z-50 border-b border-borderSubtle">
    <div class="flex items-center gap-2 sm:gap-3">
      <div class="w-8 h-8 sm:w-10 sm:h-10 transition-transform hover:rotate-6 flex-shrink-0">
        <img src="/lp/logo.png" alt="Logo CCSO" class="w-full h-full object-contain">
      </div>
      <h1 class="text-[10px] sm:text-xs font-bold tracking-wider uppercase leading-tight text-textMain">
        Central Cyber <br><span class="text-brand">Security Office</span>
      </h1>
    </div>

    <div class="flex gap-2 sm:gap-4">
      <a href="{{ route('login') }}">
        <button class="border border-borderSubtle px-3 sm:px-5 py-1.5 sm:py-2 text-[10px] sm:text-xs rounded font-medium hover:bg-surface hover:border-textMuted transition-all duration-200">Login</button>
      </a>
      <a href="#footer">
        <button class="bg-surface border border-borderSubtle px-3 sm:px-5 py-1.5 sm:py-2 text-[10px] sm:text-xs rounded font-medium hover:bg-borderSubtle text-brand transition-all duration-200">Contact Us</button>
      </a>
    </div>
  </header>

  <!-- ══════════════════════════════════════════
       HERO
  ══════════════════════════════════════════ -->
  <section class="reveal reveal-left relative overflow-hidden">
    <div class="bg-gradient-to-r from-surface via-[#171922] to-[#141b2e] px-6 sm:px-10 md:px-16 py-12 lg:py-0 lg:h-[540px] flex flex-col lg:flex-row items-center justify-between border-b border-borderSubtle gap-10 lg:gap-8">

      <!-- Teks kiri -->
      <div class="w-full lg:max-w-xl">
        <span class="text-xs font-bold uppercase tracking-widest text-brand bg-brand/10 px-3 py-1 rounded-full">Next-Gen Protection</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4 mt-3 text-textMain leading-tight">
          Central Cyber <br>Security Office
        </h2>
        <p class="text-textMuted text-base sm:text-lg leading-relaxed">
          Unified XDR dan SIEM protection untuk endpoints dan cloud workloads. Pantau dan amankan aset digital Anda dari satu titik kendali.
        </p>
        <div class="flex flex-wrap gap-4 mt-8 lg:mt-10">
          <a href="{{ route('login') }}">
            <button class="bg-brand px-6 py-3 rounded text-white font-semibold hover:bg-brandHover shadow-lg hover:shadow-brand/20 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-300">
              Mulai Sekarang
            </button>
          </a>
          <a href="#footer">
            <button class="bg-surface/50 border border-borderSubtle px-6 py-3 rounded text-textMain font-medium hover:bg-borderSubtle transition-all duration-200">
              Hubungi Kami
            </button>
          </a>
        </div>
      </div>

      <!-- Desktop Carousel (lg+) -->
      <div class="carousel-wrap" id="heroCarousel">
        <div class="carousel-track" id="carouselTrack"></div>
      </div>
    </div>
  </section>

  <!-- Mobile card scroll (< lg) -->
  <div class="mobile-cards-scroll" id="mobileCards"></div>

  <!-- ══════════════════════════════════════════
       DASHBOARD SECTION
  ══════════════════════════════════════════ -->
  <section class="text-center px-6 sm:px-10 py-12 sm:py-16 reveal reveal-up">
    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight mb-4 text-textMain">Dashboard One For All</h2>
    <p class="text-textMuted text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
      CCSO menyediakan platform dasbor terpusat untuk menampilkan visualisasi analitik dan berbagai data statistik keamanan Anda secara real-time.
    </p>
  </section>

  <!-- ══════════════════════════════════════════
       FEATURE CARDS
  ══════════════════════════════════════════ -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 px-6 sm:px-10 pb-12 sm:pb-16">

    <div class="feat-card bg-surface border border-borderSubtle p-6 sm:p-8 rounded-xl text-center hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-[border-color,box-shadow] duration-300 group">
      <div class="w-12 h-12 sm:w-14 sm:h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-4 sm:mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur1.png" class="w-6 h-6 sm:w-7 sm:h-7">
      </div>
      <h4 class="text-base sm:text-lg font-semibold mb-2">Real-Time Monitoring</h4>
      <p class="text-textMuted text-sm leading-relaxed">Pantau dan awasi aliran data serta anomali keamanan secara instan kapan saja.</p>
    </div>

    <div class="feat-card bg-surface border border-borderSubtle p-6 sm:p-8 rounded-xl text-center hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-[border-color,box-shadow] duration-300 group">
      <div class="w-12 h-12 sm:w-14 sm:h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-4 sm:mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur2.png" class="w-6 h-6 sm:w-7 sm:h-7">
      </div>
      <h4 class="text-base sm:text-lg font-semibold mb-2">Customizable Layout</h4>
      <p class="text-textMuted text-sm leading-relaxed">Atur tata letak komponen widget dasbor sesuai dengan preferensi analisis tim Anda.</p>
    </div>

    <div class="feat-card bg-surface border border-borderSubtle p-6 sm:p-8 rounded-xl text-center hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-[border-color,box-shadow] duration-300 group sm:col-span-2 lg:col-span-1 sm:max-w-sm sm:mx-auto lg:max-w-none w-full">
      <div class="w-12 h-12 sm:w-14 sm:h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-4 sm:mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur3.png" class="w-6 h-6 sm:w-7 sm:h-7">
      </div>
      <h4 class="text-base sm:text-lg font-semibold mb-2">Efficient Analysis</h4>
      <p class="text-textMuted text-sm leading-relaxed">Proses dan bedah data ancaman siber secara cepat dengan mesin komputasi modern.</p>
    </div>

  </section>

  <!-- ══════════════════════════════════════════
       ABOUT SECTION
  ══════════════════════════════════════════ -->
  <section class="px-6 sm:px-10 py-12 sm:py-16 reveal reveal-up bg-surface/30 border-y border-borderSubtle">
    <div class="max-w-6xl mx-auto flex flex-col lg:flex-row justify-between items-center gap-10 lg:gap-12">
      <div class="w-full lg:w-1/2">
        <img src="/lp/grafik2.png" class="mx-auto max-w-xs sm:max-w-md w-full drop-shadow-[0_10px_20px_rgba(0,0,0,0.3)]">
      </div>
      <div class="w-full lg:w-1/2 bg-surface border border-borderSubtle rounded-2xl p-6 sm:p-10 shadow-xl relative">
        <div class="absolute top-0 left-0 w-2 h-full bg-brand rounded-l-2xl"></div>
        <h3 class="text-xl sm:text-2xl font-bold mb-4 text-textMain">Tentang CCSO</h3>
        <p class="text-sm sm:text-base text-textMuted leading-relaxed">
          Central Cyber Security Office (CCSO) menyajikan solusi pemantauan cerdas terpadu. Melalui visualisasi informatif, kami membantu perusahaan mendeteksi ancaman lebih awal sebelum berdampak pada kelangsungan bisnis.
        </p>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       FAQ / KEUNGGULAN
  ══════════════════════════════════════════ -->
  <section class="px-6 sm:px-10 py-12 sm:py-16 max-w-4xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8 text-center text-textMain">Keunggulan Utama Platform</h2>

    <div class="space-y-4">
      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200">
        <summary class="p-4 sm:p-5 cursor-pointer font-semibold text-sm sm:text-base text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Open Source Security</span>
          <span class="text-brand faq-arrow">↓</span>
        </summary>
        <p class="p-4 sm:p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Platform kami dikembangkan di atas fondasi ekosistem terbuka yang transparan, aman, dan terus divalidasi oleh komunitas ahli siber global.
        </p>
      </details>

      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200">
        <summary class="p-4 sm:p-5 cursor-pointer font-semibold text-sm sm:text-base text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Transparansi &amp; Fleksibilitas</span>
          <span class="text-brand faq-arrow">↓</span>
        </summary>
        <p class="p-4 sm:p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Anda memegang kendali penuh atas data tanpa adanya vendor lock-in, memungkinkan kustomisasi arsitektur secara fleksibel.
        </p>
      </details>

      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200">
        <summary class="p-4 sm:p-5 cursor-pointer font-semibold text-sm sm:text-base text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Dokumentasi Lengkap</span>
          <span class="text-brand faq-arrow">↓</span>
        </summary>
        <p class="p-4 sm:p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Akses panduan integrasi API, konfigurasi agen perlindungan, hingga playbook mitigasi insiden secara menyeluruh.
        </p>
      </details>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       FOOTER
  ══════════════════════════════════════════ -->
  <footer id="footer" class="bg-surface border-t border-borderSubtle px-4 sm:px-7 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-6 items-start">

      <!-- Kiri: Branding + Deskripsi + Sosial -->
      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
          <img src="/lp/logo.png" class="h-8 transition-transform hover:scale-105" alt="Logo CCSO">
          <div>
            <p class="text-sm font-bold text-textMain leading-tight">CCSO</p>
            <p class="text-[10px] text-textMuted leading-tight">Central Cyber Security Office</p>
          </div>
        </div>

        <p class="text-xs text-textMuted leading-relaxed">
          Solusi keamanan siber terpadu untuk perlindungan data korporasi, pencegahan ancaman lanjut, dan kepatuhan regulasi keamanan informasi.
        </p>

        <!-- Ikon Sosial -->
        <div class="flex items-center gap-2 mt-5">
          <a href="#" target="_blank"
             class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 transition-all opacity-70 hover:opacity-100">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="2" width="20" height="20" rx="5"/>
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
          </a>
          <a href="#" target="_blank"
             class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 transition-all opacity-70 hover:opacity-100">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/>
              <rect x="2" y="9" width="4" height="12"/>
              <circle cx="4" cy="4" r="2"/>
            </svg>
          </a>
          <a href="https://wa.me/621234567890" target="_blank"
             class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 transition-all opacity-70 hover:opacity-100">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12.002 2.002C6.477 2.002 2 6.477 2 12c0 1.85.505 3.58 1.383 5.065L2 22l5.05-1.367A9.955 9.955 0 0012.002 22C17.525 22 22 17.523 22 12c0-5.522-4.475-9.998-9.998-9.998zm0 18.18a8.165 8.165 0 01-4.17-1.146l-.299-.178-3.1.84.836-3.019-.195-.309A8.163 8.163 0 013.822 12c0-4.518 3.664-8.18 8.18-8.18 4.518 0 8.18 3.662 8.18 8.18s-3.662 8.182-8.18 8.182z"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Tengah: Navigasi -->
      <div class="flex flex-col gap-3 sm:items-center">
        <p class="text-[10px] font-semibold tracking-widest text-textMuted uppercase mb-1">Navigasi</p>
        <a href="#hero"       class="text-sm text-brand font-medium transition-colors">Beranda</a>
        <a href="#about"      class="text-sm text-textMuted hover:text-textMain transition-colors">Tentang CCSO</a>
        <a href="#features"   class="text-sm text-textMuted hover:text-textMain transition-colors">Fitur Platform</a>
        <a href="#advantages" class="text-sm text-textMuted hover:text-textMain transition-colors">Keunggulan Utama</a>
        <a href="#contact"    class="text-sm text-textMuted hover:text-textMain transition-colors">Kontak</a>
      </div>

      <!-- Kanan: Akses Cepat -->
      <div class="flex flex-col gap-3">
        <p class="text-[10px] font-semibold tracking-widest text-textMuted uppercase mb-1">Akses Cepat</p>

        <a href="{{ route('login') }}"
           class="w-full flex items-center justify-between px-5 py-3 rounded-xl border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 text-textMain hover:text-brand text-sm font-medium transition-all group">
          <span class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="opacity-70">
              <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
              <polyline points="10 17 15 12 10 7"/>
              <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Login
          </span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-all">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>

        <a href="https://wa.me/621234567890" target="_blank"
           class="w-full flex items-center justify-between px-5 py-3 rounded-xl border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 text-textMain hover:text-brand text-sm font-medium transition-all group">
          <span class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="opacity-70">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12.002 2.002C6.477 2.002 2 6.477 2 12c0 1.85.505 3.58 1.383 5.065L2 22l5.05-1.367A9.955 9.955 0 0012.002 22C17.525 22 22 17.523 22 12c0-5.522-4.475-9.998-9.998-9.998zm0 18.18a8.165 8.165 0 01-4.17-1.146l-.299-.178-3.1.84.836-3.019-.195-.309A8.163 8.163 0 013.822 12c0-4.518 3.664-8.18 8.18-8.18 4.518 0 8.18 3.662 8.18 8.18s-3.662 8.182-8.18 8.182z"/>
            </svg>
            Hubungi Kami
          </span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-all">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

    </div>

    <!-- Garis + Copyright + Links -->
    <div class="max-w-7xl mx-auto mt-4 pt-3 border-t border-borderSubtle flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
      <p class="text-[11px] text-textMuted">© 2026 CCSO, Inc. All rights reserved.</p>
      <div class="flex items-center gap-4">
        <a href="#" class="text-[11px] text-textMuted hover:text-textMain transition-colors">Kebijakan Privasi</a>
        <a href="#" class="text-[11px] text-textMuted hover:text-textMain transition-colors">Syarat & Ketentuan</a>
      </div>
    </div>
  </footer>

  <!-- ══════════════════════════════════════════
       SCRIPTS
  ══════════════════════════════════════════ -->

  <!-- Scroll Reveal -->
  <script>
    const reveals = document.querySelectorAll('.reveal');
    function revealOnScroll() {
      const wh = window.innerHeight;
      reveals.forEach((el, i) => {
        const top = el.getBoundingClientRect().top;
        const bot = el.getBoundingClientRect().bottom;
        if (top < wh - 80 && bot > 0) {
          setTimeout(() => el.classList.add('active'), i * 60);
        } else {
          el.classList.remove('active');
        }
      });
    }
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
  </script>

  <!-- Anime.js Feature Cards -->
  <script type="module">
    import { animate, utils } from 'https://esm.sh/animejs';
    const cards   = document.querySelectorAll('.feat-card');
    const section = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3');
    let isInView  = false;

    function resetCards() {
      cards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(40px) scale(0.85)'; });
    }
    function runCardAnimation() {
      animate(cards, {
        opacity:      [0, 1],
        translateY:   (el, i) => [`${50 + (-20*i)}px`, '0px'],
        scale:        (el, i, t) => [(t.length - i) * 0.3 + 0.3, 1],
        rotate:       [() => utils.random(-12, 12), 0],
        borderRadius: ['24px', '12px'],
        duration:     () => utils.random(1200, 1800),
        delay:        (el, i) => i * 130 + utils.random(0, 60),
        ease:         'outElastic(1, .5)',
      });
    }
    function checkScroll() {
      if (!section) return;
      const rect = section.getBoundingClientRect();
      const now  = rect.top < window.innerHeight - 60 && rect.bottom > 0;
      if (now && !isInView)  { isInView = true;  runCardAnimation(); }
      if (!now && isInView)  { isInView = false; resetCards(); }
    }
    window.addEventListener('scroll', checkScroll, { passive: true });
    setTimeout(checkScroll, 150);
  </script>

  <!-- Card data + Carousel + Mobile scroll builder -->
  <script>
    const cardData = [
      { label:'Security Alerts',      color:'#ef4444', icon:'<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/>',  title:'Live Alerts',    desc:'Notifikasi instan atas aktivitas mencurigakan.' },
      { label:'Agent Status',         color:'#22c55e', icon:'<rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/>',             title:'All Online',     desc:'Pantau status koneksi seluruh agent.', pulse:true },
      { label:'Top Triggered Rules',  color:'#f59e0b', icon:'<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/>',                                                                              title:'Top Rules',      desc:'Rule keamanan yang paling sering terpicu.' },
      { label:'Failed Login Monitoring', color:'#ef4444', icon:'<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>',                                                                                                       title:'Failed Login',   desc:'Lacak percobaan login yang gagal.' },
      { label:'System Resources',     color:'#6366f1', icon:'<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/>',                               title:'Resource Usage', desc:'Monitor CPU, memori, dan disk real-time.' },
      { label:'Network Traffic',      color:'#06b6d4', icon:'<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',                                                                                                                                           title:'Traffic Flow',   desc:'Visualisasi lalu lintas data jaringan.' },
      { label:'Service Status',       color:'#22c55e', icon:'<path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',                                                                                                        title:'Running',        desc:'Status layanan sistem secara real-time.' },
      { label:'File Integrity Monitoring', color:'#6366f1', icon:'<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15l2 2 4-4"/>',                                                           title:'FIM Active',     desc:'Deteksi perubahan file secara otomatis.' },
      { label:'Active Connections',   color:'#06b6d4', icon:'<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>',                                                   title:'Connections',    desc:'Daftar koneksi aktif saat ini.' },
      { label:'Firewall Events',      color:'#f59e0b', icon:'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',                                                                                                                                       title:'Firewall Log',   desc:'Catatan aktivitas firewall terkini.' },
      { label:'User Login Activity',  color:'#a855f7', icon:'<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',                                                                                                             title:'Login History',  desc:'Riwayat aktivitas login pengguna.' },
      { label:'GeoIP Attack Map',     color:'#a855f7', icon:'<circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',                      title:'Attack Map',     desc:'Peta visual asal serangan berdasarkan lokasi.' },
    ];

    function makeSVG(path, color) {
      return `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">${path}</svg>`;
    }

    /* ── Build desktop carousel cards ── */
    const track = document.getElementById('carouselTrack');
    cardData.forEach((d, i) => {
      const card = document.createElement('div');
      card.className = 'c-card';
      const pos = i === 0 ? 'center' : i === 1 ? 'right' : 'hidden-right';
      card.setAttribute('data-pos', pos);
      card.setAttribute('data-idx', i);

      const titleRow = d.pulse
        ? `<div style="display:flex;align-items:center;gap:6px;margin-top:auto;">
             <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;" class="pulse-dot-anim"></span>
             <span style="font-size:18px;font-weight:800;color:#f1f3f9;">${d.title}</span>
           </div>`
        : `<p style="font-size:18px;font-weight:800;line-height:1.3;color:#f1f3f9;margin-top:auto;">${d.title}</p>`;

      card.innerHTML = `<div class="c-card-inner">
        <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:${d.color};margin-bottom:12px;">${d.label}</p>
        <div style="width:38px;height:38px;border-radius:10px;background:${d.color}1a;border:1px solid ${d.color}40;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">${makeSVG(d.icon, d.color)}</div>
        ${titleRow}
        <p style="font-size:11px;color:#787f99;margin-top:6px;">${d.desc}</p>
      </div>`;
      track.appendChild(card);
    });

    /* ── Build mobile horizontal scroll cards (duplikat untuk infinite loop) ── */
    const mobileWrap = document.getElementById('mobileCards');
    // Render 2x agar loop terasa mulus
    [cardData, cardData].forEach(set => {
      set.forEach(d => {
        const card = document.createElement('div');
        card.className = 'm-card';
        card.innerHTML = `
          <div class="m-card-label" style="color:${d.color}">${d.label}</div>
          <div class="m-card-icon" style="background:${d.color}1a;border:1px solid ${d.color}40">${makeSVG(d.icon, d.color)}</div>
          <div class="m-card-title">${d.title}</div>
          <div class="m-card-desc">${d.desc}</div>
        `;
        mobileWrap.appendChild(card);
      });
    });

    /* ── Mobile auto-scroll (infinite loop, tidak lawan swipe user) ── */
    (function() {
      const wrap      = mobileWrap;
      const SPEED     = 0.6;  // px per frame
      const RESUME_MS = 900;  // jeda (ms) setelah jari lepas sebelum auto lanjut

      let auto        = true;   // apakah sedang mode auto
      let pos         = 0;      // posisi auto (dikelola terpisah dari scrollLeft)
      let resumeTimer = null;

      function getSetWidth() { return wrap.scrollWidth / 2; }

      /* Tiap frame: hanya tulis scrollLeft kalau auto aktif */
      function tick() {
        if (auto) {
          pos += SPEED;
          const setW = getSetWidth();
          if (pos >= setW) pos -= setW;
          wrap.scrollLeft = pos;
        }
        requestAnimationFrame(tick);
      }

      function stopAuto() {
        auto = false;
        clearTimeout(resumeTimer);
      }

      function scheduleResume() {
        /* Ambil posisi scroll saat ini (sudah digeser user) sebagai titik start auto */
        pos = wrap.scrollLeft % getSetWidth();
        resumeTimer = setTimeout(() => { auto = true; }, RESUME_MS);
      }

      /* ── Touch (mobile) ── */
      wrap.addEventListener('touchstart',  stopAuto,       { passive: true });
      wrap.addEventListener('touchend',    scheduleResume, { passive: true });
      wrap.addEventListener('touchcancel', scheduleResume, { passive: true });

      requestAnimationFrame(tick);
    })();

    /* ── Desktop carousel auto-rotate ── */
    (function() {
      const cards = Array.from(document.querySelectorAll('.c-card'));
      const total = cards.length;
      let centerIdx = 0;

      function getPos(i, center) {
        const diff = (i - center + total) % total;
        if (diff === 0)                      return 'center';
        if (diff === 1)                      return 'right';
        if (diff === total - 1)              return 'left';
        if (diff <= Math.floor(total / 2))   return 'hidden-right';
        return 'hidden-left';
      }
      function applyPositions(center) {
        cards.forEach((c, i) => c.setAttribute('data-pos', getPos(i, center)));
      }
      applyPositions(centerIdx);
      setInterval(() => { centerIdx = (centerIdx + 1) % total; applyPositions(centerIdx); }, 1300);
    })();
  </script>

</body>
</html>