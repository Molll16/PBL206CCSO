<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>landing page- Central Cyber Security Office</title>
  <link rel="stylesheet" href="../css/landingpage.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes animasilp {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

html {
    animation: animasilp 300ms ease-in-out;
    scroll-behavior: smooth;
}

.reveal {
    opacity: 0;
    transition: all 0.8s ease;
}

.reveal-up {
    transform: translateY(40px);
}

.reveal-left {
    transform: translateX(-60px);
}

.reveal-right {
    transform: translateX(60px);
}

.reveal.active {
    opacity: 1;
    transform: translate(0, 0);
}
  </style>


  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            tahoma: ['Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
          },
        }
      }
    }
  </script>
</head>

<body class="bg-[#2B2D34] text-white font-tahoma overflow-x-hidden">

  <!-- Navbar -->
  <header class="flex justify-between items-center px-7 py-4 bg-[#212121]">
    <div class="flex items-center gap-2">
      <div class="w-10 h-10">
        <img src="/lp/logo.png">
      </div>
      <h1 class="text-xs">Central Cyber <br>Security Office</h1>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('login') }}"> <button class="border px-5 py-2 text-xs rounded hover:bg-white hover:text-black">Login</button> </a>
      <a href="#footer"> <button class="border px-5 py-2 text-xs rounded hover:bg-white hover:text-black">Contact Us</button> </a>
    </div>
    
  </header>

  <!-- Hero -->
  <section class="font-tahoma reveal reveal-left">
    <div class="bg-gradient-to-r from-[#2b2d34] via-[#174a6b] to-[#1f5c7d] p-14 flex">

      <div>
        <h2 class="text-3xl mb-4">Central Cyber Security Office</h2>
        <p>Unified XDR and SIEM protection for endpoints and cloud workloads.</p>
        <div class="flex gap-4 mt-28">
          <a href="{{ route('login') }}"> <button class="bg-[#3282B8] px-5 py-2 rounded hover:bg-blue-600">Login</button> </a>
          <a href="#footer"><button class="bg-[#3282B8] px-5 py-2 rounded hover:bg-blue-500">Contact Us</button></a>
        </div>
      </div>

      <div>
        <img src="/lp/grafik.png" class="w-full ml-12">
      </div>

    </div>
  </section>

  <!-- Dashboard -->
  <section class="text-center px-10 py-10 reveal reveal-up">
    <h2 class="text-2xl mb-3">Dashboard One For All</h2>
    <p class="text-gray-400 max-w-2xl mx-auto">
      is a centralized dashboard platform to display various statistical data.
    </p>
  </section>

  <!-- Features -->
  <section class="grid md:grid-cols-3 gap-6 px-10 pb-12">

    <div class="border p-6 rounded-lg text-center reveal reveal-left">
      <img src="/lp/fitur1.png" class="w-12 h-12 mx-auto mb-4">
      <p>Display and monitor data in real-time.</p>
    </div>

    <div class="border p-6 rounded-lg text-center reveal reveal-up">
      <img src="/lp/fitur2.png" class="w-12 h-12 mx-auto mb-4">
      <p>Set up display and customize layout.</p>
    </div>

    <div class="border p-6 rounded-lg text-center reveal reveal-right">
      <img src="/lp/fitur3.png" class="w-12 h-12 mx-auto mb-4">
      <p>Analyze data efficiently.</p>
    </div>

  </section>

  <!-- About -->
  <section class="px-10 py-12 reveal reveal-up">
    <div class="relative max-w-6xl mx-auto flex justify-center items-center">

      <div class="w-[550px]">
        <img src="/lp/grafik2.png" class="mx-auto">
      </div>

      <div class="ml-auto w-[70%] border rounded-xl p-8 text-center">
        <h3 class="text-2xl mb-4">About (CCSO)</h3>
        <p class="text-lg">
          Display and monitor data in real-time with informative visualization.
        </p>
      </div>

    </div>
  </section>

  <!-- Accordion -->
  <section class="px-10 py-10">
    <h2 class="text-xl mb-4 text-center">About (CCSO)</h2>

    <div class="space-y-3">

      <details class="border border-[#3282B8] rounded">
        <summary class="p-4 cursor-pointer">Open Source Security</summary>
        <p class="p-4 text-sm">
          is a centralized dashboard platform for displaying various statistical data.
        </p>
      </details>

      <details class="border border-[#3282B8] rounded">
        <summary class="p-4 cursor-pointer">Transparency and Flexibility</summary>
        <p class="p-4 text-sm">Content here...</p>
      </details>

      <details class="border border-[#3282B8] rounded">
        <summary class="p-4 cursor-pointer">Documentation</summary>
        <p class="p-4 text-sm">Content here...</p>
      </details>

      <details class="border border-[#3282B8] rounded">
        <summary class="p-4 cursor-pointer">Community</summary>
        <p class="p-4 text-sm">Content here...</p>
      </details>

    </div>
  </section>

  <!-- Testimonial -->
  <section class="text-center">
    <img src="/lp/bgcolor2.png" class="absolute object-cover -z-10">

    <div class="py-10 px-6">
      <h2 class="text-xl mb-8">What our customers say about us</h2>

      <div class="flex items-center justify-between gap-4 mx-4">

        <div class="mb-10">
          <img src="/lp/left.png" class="cursor-pointer">
        </div>

        <div class="flex flex-col items-center w-40">
          <img src="/lp/profile.png" class="w-16 h-16 rounded-full mb-2">
          <h4 class="font-tahoma">Name</h4>
          <p class="text-gray-400 text-sm mt-1">Customer email here</p>
        </div>

        <div class="text-left text-sm leading-relaxed max-w-xl">
          <p>
            is a centralized dashboard platform for displaying various statistical
            data in a concise and easy-to-understand manner, with customizable
            features to suit monitoring needs. is a centralized dashboard platform
            for displaying various statistical data in a concise and easy-to-
            understand manner, with customizable features to suit monitoring
            needs. is a centralized dashboard platform for displaying various
            statistical data in a concise and easy-to-understand manner, with
            customizable features to suit monitoring needs.
          </p>
        </div>

        <div class="mb-10">
          <img src="/lp/right.png" class="cursor-pointer">
        </div>

      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-[#212121] px-10 py-10">

    <div class="grid md:grid-cols-3 gap-8">

      <div>
        <h3 class="mb-3 font-semibold">Central Cyber Security Office</h3>
        <ul class="text-gray-400 space-y-2 text-sm">
          <li>About</li>
          <li>Feature</li>
          <li>Learn More</li>
          <li>Review</li>
          <li>Contact Us</li>
        </ul>
      </div>

      <div>
        <h3 class="mb-3 font-semibold">Services</h3>
        <ul class="text-gray-400 space-y-2 text-sm">
          <li>CCSO Cloud</li>
          <li>Professional Support</li>
          <li>Consulting Services</li>
          <li>Training Course</li>
        </ul>
      </div>

      <div>
        <h3 class="mb-3 font-semibold">Resources</h3>
        <ul class="text-gray-400 space-y-2 text-sm">
          <li>Blog</li>
          <li>Company</li>
          <li>Legal</li>
        </ul>
      </div>

    </div>

    <img src="/lp/garis2.png" class="mt-8 w-full">
    
    <div id="footer">
      
    <div class="mt-8 px-1 flex flex-wrap items-center gap-4 text-sm text-gray-400">

      <!-- logo -->
      <img src="/lp/logo.png" class="h-10">

      <p class="text-white">© 2026 CCSO, Inc.</p>

      <!-- garistegak -->
      <img src="/lp/garis.png" class="h-5">

      <p class="text-white">Contact Us</p>

      <!-- garistegak -->
      <img src="/lp/garis.png" class="h-5">

      <!-- telp -->
      <div class="flex items-center gap-2">
        <img src="/lp/telp.png" class="h-5">
        <p>+62 1234567890</p>
      </div>

      <!-- medsos -->
      <div class="flex items-center gap-6 ml-2">
        <img src="/lp/tt.png" class="h-5 cursor-pointer">
        <img src="/lp/ig.png" class="h-5 cursor-pointer">
        <img src="/lp/wa.png" class="h-5 cursor-pointer">
        <img src="/lp/email.png" class="h-5 cursor-pointer">
      </div>

      <!-- Email input -->
      <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
        <input
          type="email"
          placeholder="Sent to our Email..."
          class="bg-transparent px-3 py-1 text-sm w-54">
        <button class="bg-blue-500 px-3 py-1 text-white text-sm hover:bg-blue-600">›</button>
      </div>

    </div>
  </footer>
  
<script>
  const reveals = document.querySelectorAll('.reveal');

  function revealOnScroll() {
    const windowHeight = window.innerHeight;

    reveals.forEach((el, index) => {
      const elementTop = el.getBoundingClientRect().top;
      const elementBottom = el.getBoundingClientRect().bottom;

      if (elementTop < windowHeight - 100 && elementBottom > 0) {
        setTimeout(() => {
          el.classList.add('active');
        }, index * 80);
      } else {
        el.classList.remove('active'); // 🔥 ini bikin animasi muncul lagi saat scroll balik
      }
    });
  }

  window.addEventListener('scroll', revealOnScroll);
  revealOnScroll();
</script>

</body>
</html>