<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CCSO</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @vite(['resources/css/landingpage.css', 'resources/js/landingpage.js'])
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

  <header
    class="flex justify-between items-center px-4 sm:px-8 py-3 sm:py-4 bg-surface/80 backdrop-blur-md sticky top-0 z-50 border-b border-borderSubtle">
    <div class="flex items-center gap-2 sm:gap-3">
      <div class="w-8 h-8 sm:w-10 sm:h-10 transition-transform hover:rotate-6 flex-shrink-0">
        <img src="/lp/logo.png" alt="CCSO Logo" class="w-full h-full object-contain">
      </div>
      <h1 class="text-[10px] sm:text-xs font-bold tracking-wider uppercase leading-tight text-textMain">
        Central Cyber <br><span class="text-brand">Security Office</span>
      </h1>
    </div>

    <div class="flex gap-2 sm:gap-4">
      <a href="{{ route('login') }}">
        <button
          class="border border-borderSubtle px-3 sm:px-5 py-1.5 sm:py-2 text-[10px] sm:text-xs rounded font-medium hover:bg-surface hover:border-textMuted transition-all duration-200">Login</button>
      </a>
      <a href="#footer">
        <button
          class="bg-surface border border-borderSubtle px-3 sm:px-5 py-1.5 sm:py-2 text-[10px] sm:text-xs rounded font-medium hover:bg-borderSubtle text-brand transition-all duration-200">Contact
          Us</button>
      </a>
    </div>
  </header>

  <section class="reveal reveal-left relative overflow-hidden">
    <div
      class="bg-gradient-to-r from-surface via-[#171922] to-[#141b2e] px-6 sm:px-10 md:px-16 py-12 lg:py-0 lg:h-[540px] flex flex-col lg:flex-row items-center justify-between border-b border-borderSubtle gap-10 lg:gap-8">

      <div class="w-full lg:max-w-xl">
        <span class="text-xs font-bold uppercase tracking-widest text-brand bg-brand/10 px-3 py-1 rounded-full">Next-Gen
          Protection</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4 mt-3 text-textMain leading-tight">
          Central Cyber <br>Security Office
        </h2>
        <p class="text-textMuted text-base sm:text-lg leading-relaxed">
          Unified XDR and SIEM protection for endpoints and cloud workloads. Monitor and secure your digital assets from
          a single point of control.
        </p>
        <div class="flex flex-wrap gap-4 mt-8 lg:mt-10">
          <a href="{{ route('login') }}">
            <button
              class="bg-brand px-6 py-3 rounded text-white font-semibold hover:bg-brandHover shadow-lg hover:shadow-brand/20 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-300">
              Get Started Now
            </button>
          </a>
          <a href="#footer">
            <button
              class="bg-surface/50 border border-borderSubtle px-6 py-3 rounded text-textMain font-medium hover:bg-borderSubtle transition-all duration-200">
              Contact Us
            </button>
          </a>
        </div>
      </div>

      <div class="carousel-wrap" id="heroCarousel">
        <div class="carousel-track" id="carouselTrack"></div>
      </div>
    </div>
  </section>

  <div class="mobile-cards-scroll" id="mobileCards"></div>

  <section class="text-center px-6 sm:px-10 py-12 sm:py-16 reveal reveal-up">
    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight mb-4 text-textMain">One For All Dashboard</h2>
    <p class="text-textMuted text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
      CCSO provides a centralized dashboard platform to display your analytics visualization and various real-time
      security statistical data.
    </p>
  </section>

  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 px-6 sm:px-10 pb-12 sm:pb-16">

    <div
      class="feat-card bg-surface border border-borderSubtle p-6 sm:p-8 rounded-xl text-center hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-[border-color,box-shadow] duration-300 group">
      <div
        class="w-12 h-12 sm:w-14 sm:h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-4 sm:mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur1.png" class="w-6 h-6 sm:w-7 sm:h-7">
      </div>
      <h4 class="text-base sm:text-lg font-semibold mb-2">Real-Time Monitoring</h4>
      <p class="text-textMuted text-sm leading-relaxed">Instantly monitor and track data streams and security anomalies
        at any time.</p>
    </div>

    <div
      class="feat-card bg-surface border border-borderSubtle p-6 sm:p-8 rounded-xl text-center hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-[border-color,box-shadow] duration-300 group">
      <div
        class="w-12 h-12 sm:w-14 sm:h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-4 sm:mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur2.png" class="w-6 h-6 sm:w-7 sm:h-7">
      </div>
      <h4 class="text-base sm:text-lg font-semibold mb-2">Customizable Layout</h4>
      <p class="text-textMuted text-sm leading-relaxed">Arrange the layout of your dashboard widgets according to your
        team's analysis preferences.</p>
    </div>

    <div
      class="feat-card bg-surface border border-borderSubtle p-6 sm:p-8 rounded-xl text-center hover:-translate-y-2 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-[border-color,box-shadow] duration-300 group sm:col-span-2 lg:col-span-1 sm:max-w-sm sm:mx-auto lg:max-w-none w-full">
      <div
        class="w-12 h-12 sm:w-14 sm:h-14 bg-page rounded-lg flex items-center justify-center mx-auto mb-4 sm:mb-5 border border-borderSubtle group-hover:border-brand/30 transition-colors">
        <img src="/lp/fitur3.png" class="w-6 h-6 sm:w-7 sm:h-7">
      </div>
      <h4 class="text-base sm:text-lg font-semibold mb-2">Efficient Analysis</h4>
      <p class="text-textMuted text-sm leading-relaxed">Process and dissect cyber threat data rapidly with modern
        computing engines.</p>
    </div>

  </section>

  <section class="px-6 sm:px-10 py-12 sm:py-16 reveal reveal-up bg-surface/30 border-y border-borderSubtle">
    <div class="max-w-6xl mx-auto flex flex-col lg:flex-row justify-between items-center gap-10 lg:gap-12">
      <div class="w-full lg:w-1/2">
        <img src="/lp/grafik2.png"
          class="mx-auto max-w-xs sm:max-w-md w-full drop-shadow-[0_10px_20px_rgba(0,0,0,0.3)]">
      </div>
      <div class="w-full lg:w-1/2 bg-surface border border-borderSubtle rounded-2xl p-6 sm:p-10 shadow-xl relative">
        <div class="absolute top-0 left-0 w-2 h-full bg-brand rounded-l-2xl"></div>
        <h3 class="text-xl sm:text-2xl font-bold mb-4 text-textMain">About CCSO</h3>
        <p class="text-sm sm:text-base text-textMuted leading-relaxed">
          The Central Cyber Security Office (CCSO) provides an integrated smart monitoring solution. Through informative
          visualizations, we help businesses detect threats early before they impact operational continuity.
        </p>
      </div>
    </div>
  </section>

  <section class="px-6 sm:px-10 py-12 sm:py-16 max-w-4xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8 text-center text-textMain">Key Platform Advantages</h2>

    <div class="space-y-4">
      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200">
        <summary
          class="p-4 sm:p-5 cursor-pointer font-semibold text-sm sm:text-base text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Open Source Security</span>
          <span class="text-brand faq-arrow">↓</span>
        </summary>
        <p class="p-4 sm:p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Our platform is built on the foundation of an open ecosystem that is transparent, secure, and continuously
          validated by a global community of cyber experts.
        </p>
      </details>

      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200">
        <summary
          class="p-4 sm:p-5 cursor-pointer font-semibold text-sm sm:text-base text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Transparency &amp; Flexibility</span>
          <span class="text-brand faq-arrow">↓</span>
        </summary>
        <p class="p-4 sm:p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          You retain full control over your data without any vendor lock-in, enabling flexible architectural
          customization.
        </p>
      </details>

      <details class="bg-surface border border-borderSubtle rounded-lg group transition-all duration-200">
        <summary
          class="p-4 sm:p-5 cursor-pointer font-semibold text-sm sm:text-base text-textMain hover:bg-borderSubtle/50 transition-colors list-none flex justify-between items-center">
          <span>Comprehensive Documentation</span>
          <span class="text-brand faq-arrow">↓</span>
        </summary>
        <p class="p-4 sm:p-5 text-sm text-textMuted border-t border-borderSubtle leading-relaxed bg-page/30">
          Access thorough API integration guides, protection agent configurations, and incident mitigation playbooks.
        </p>
      </details>
    </div>
  </section>

  <footer id="footer" class="bg-surface border-t border-borderSubtle px-4 sm:px-7 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-6 items-start">

      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
          <img src="/lp/logo.png" class="h-8 transition-transform hover:scale-105" alt="CCSO Logo">
          <div>
            <p class="text-sm font-bold text-textMain leading-tight">CCSO</p>
            <p class="text-[10px] text-textMuted leading-tight">Central Cyber Security Office</p>
          </div>
        </div>

        <p class="text-xs text-textMuted leading-relaxed">
          Integrated cybersecurity solutions for corporate data protection, advanced threat prevention, and information
          security regulatory compliance.
        </p>

        <div class="flex items-center gap-2 mt-5">
          <a href="#" target="_blank"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 transition-all opacity-70 hover:opacity-100">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="2" width="20" height="20" rx="5" />
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
            </svg>
          </a>
          <a href="#" target="_blank"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 transition-all opacity-70 hover:opacity-100">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z" />
              <rect x="2" y="9" width="4" height="12" />
              <circle cx="4" cy="4" r="2" />
            </svg>
          </a>
          <a href="https://wa.me/621234567890" target="_blank"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 transition-all opacity-70 hover:opacity-100">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
              <path
                d="M12.002 2.002C6.477 2.002 2 6.477 2 12c0 1.85.505 3.58 1.383 5.065L2 22l5.05-1.367A9.955 9.955 0 0012.002 22C17.525 22 22 17.523 22 12c0-5.522-4.475-9.998-9.998-9.998zm0 18.18a8.165 8.165 0 01-4.17-1.146l-.299-.178-3.1.84.836-3.019-.195-.309A8.163 8.163 0 013.822 12c0-4.518 3.664-8.18 8.18-8.18 4.518 0 8.18 3.662 8.18 8.18s-3.662 8.182-8.18 8.182z" />
            </svg>
          </a>
        </div>
      </div>

      <div class="flex flex-col gap-3 sm:items-center">
        <p class="text-[10px] font-semibold tracking-widest text-textMuted uppercase mb-1">Navigation</p>
        <a href="#hero" class="text-sm text-brand font-medium transition-colors">Home</a>
        <a href="#about" class="text-sm text-textMuted hover:text-textMain transition-colors">About CCSO</a>
        <a href="#features" class="text-sm text-textMuted hover:text-textMain transition-colors">Platform Features</a>
        <a href="#advantages" class="text-sm text-textMuted hover:text-textMain transition-colors">Key Advantages</a>
        <a href="#contact" class="text-sm text-textMuted hover:text-textMain transition-colors">Contact</a>
      </div>

      <div class="flex flex-col gap-3">
        <p class="text-[10px] font-semibold tracking-widest text-textMuted uppercase mb-1">Quick Access</p>

        <a href="{{ route('login') }}"
          class="w-full flex items-center justify-between px-5 py-3 rounded-xl border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 text-textMain hover:text-brand text-sm font-medium transition-all group">
          <span class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
              stroke-linecap="round" stroke-linejoin="round" class="opacity-70">
              <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
              <polyline points="10 17 15 12 10 7" />
              <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            Login
          </span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-all">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </a>

        <a href="https://wa.me/621234567890" target="_blank"
          class="w-full flex items-center justify-between px-5 py-3 rounded-xl border border-borderSubtle hover:border-brand/40 hover:bg-brand/10 text-textMain hover:text-brand text-sm font-medium transition-all group">
          <span class="flex items-center gap-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="opacity-70">
              <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
              <path
                d="M12.002 2.002C6.477 2.002 2 6.477 2 12c0 1.85.505 3.58 1.383 5.065L2 22l5.05-1.367A9.955 9.955 0 0012.002 22C17.525 22 22 17.523 22 12c0-5.522-4.475-9.998-9.998-9.998zm0 18.18a8.165 8.165 0 01-4.17-1.146l-.299-.178-3.1.84.836-3.019-.195-.309A8.163 8.163 0 013.822 12c0-4.518 3.664-8.18 8.18-8.18 4.518 0 8.18 3.662 8.18 8.18s-3.662 8.182-8.18 8.182z" />
            </svg>
            Contact Us
          </span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-all">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </a>
      </div>

    </div>

    <div
      class="max-w-7xl mx-auto mt-4 pt-3 border-t border-borderSubtle flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
      <p class="text-[11px] text-textMuted">© 2026 CCSO, Inc. All rights reserved.</p>
      <div class="flex items-center gap-4">
        <a href="#" class="text-[11px] text-textMuted hover:text-textMain transition-colors">Privacy Policy</a>
        <a href="#" class="text-[11px] text-textMuted hover:text-textMain transition-colors">Terms &amp; Conditions</a>
      </div>
    </div>
  </footer>
</body>
</html>