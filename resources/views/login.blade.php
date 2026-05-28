<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Central Cyber Security Office</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

  <style>
    @keyframes animasilogin {
      0% {
        opacity: 0;
        transform: scale(0.95);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .page-wrapper {
      animation: animasilogin 400ms cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>

<body class="min-h-screen overflow-hidden flex flex-col font-sans relative bg-page text-textMain antialiased">

  <div class="page-wrapper relative flex flex-col min-h-screen">

    <div class="absolute inset-0 bg-gradient-to-br from-surface via-page to-page"></div>

    <div class="absolute top-[-120px] left-[-120px] w-[400px] h-[400px] bg-brand/10 blur-[100px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-[-120px] right-[-120px] w-[400px] h-[400px] bg-brand/10 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="absolute inset-0 opacity-10 pointer-events-none">
      <div class="w-full h-full bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
    </div>

    <div class="relative z-10 p-6">
      <button onclick="history.back()" class="opacity-70 hover:opacity-100 hover:-translate-x-1 transition-all duration-200">
        <img
          src="/logindark/logoexit.png"
          width="30"
          height="30"
          alt="Kembali"
        >
      </button>
    </div>

    <div class="relative z-10 flex-1 flex items-center justify-center">

      <div class="bg-surface/80 backdrop-blur-xl rounded-[24px] border border-borderSubtle px-10 py-12 flex flex-col items-center w-[400px] mb-12 shadow-[0_0_50px_rgba(99,102,241,0.08)]">

        <img
          src="/logindark/logo.png"
          width="90"
          height="90"
          class="drop-shadow-[0_0_20px_rgba(99,102,241,0.2)] mb-2"
        >
        <br>

        <form action="/login" method="POST" class="w-full">
          @csrf

          <div class="flex items-center gap-2 w-full mb-4">
            <div class="bg-page border border-borderSubtle rounded-lg p-2.5 flex-shrink-0">
              <img src="/daftarblack/logouser.png" width="22" height="22" class="opacity-80">
            </div>
            <input
              type="text"
              name="username"
              placeholder="Username"
              class="rounded-lg px-4 py-3 text-sm w-full border border-borderSubtle bg-page text-textMain placeholder-textMuted outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200"
            >
          </div>

          <div class="flex items-center gap-2 w-full mb-8">
            <div class="bg-page border border-borderSubtle rounded-lg p-2.5 flex-shrink-0">
              <img src="/logindark/logopassword.png" width="22" height="22" class="opacity-80">
            </div>
            <div class="relative w-full">
              <input
                id="password"
                type="password"
                name="password"
                placeholder="Password"
                class="rounded-lg px-4 py-3 pr-10 text-sm w-full border border-borderSubtle bg-page text-textMain placeholder-textMuted outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200"
              >
              <span
                onclick="togglePassword()"
                class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100 transition-opacity"
              >
                <img id="eyeIcon" src="/logindark/logomata.png" width="18" height="18">
              </span>
            </div>
          </div>

          <button
            type="submit"
            class="w-full py-3 rounded-lg text-white font-semibold tracking-wide bg-brand hover:bg-brandHover shadow-lg hover:shadow-brand/25 transition-all duration-300"
          >
            Login
          </button>

        </form>
      </div>
    </div>

  </div><script>
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
  </script>

</body>
</html>

@if(session('error'))
<script>
Swal.fire({
    title: 'Login Gagal',
    text: "{{ session('error') }}",
    icon: 'error',

    background: '#1a1b23', /* bg-surface */
    color: '#f1f3f9', /* text-main */

    confirmButtonText: 'OK',

    customClass: {
        /* Disesuaikan bayangannya dengan warna brand/merah khas error */
        popup: 'rounded-[24px] border border-[#262833] backdrop-blur-xl shadow-[0_0_40px_rgba(239,68,68,0.15)]',
        title: 'text-2xl font-bold text-white',
        htmlContainer: 'text-[#787f99] text-sm', /* text-muted */
        confirmButton: 'rounded-lg px-8 py-2.5 text-sm font-semibold bg-[#ef4444] hover:bg-[#dc2626] border-none' /* alert-critical */
    },

    buttonsStyling: false,

    showClass: {
        popup: `
            animate__animated
            animate__fadeInDown
            animate__faster
        `
    },

    hideClass: {
        popup: `
            animate__animated
            animate__fadeOutUp
            animate__faster
        `
    },

    backdrop: `
        rgba(18, 19, 24, 0.75) 
        backdrop-filter: blur(4px)
    `
})
</script>
@endif