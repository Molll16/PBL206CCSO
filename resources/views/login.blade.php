<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

  <style>
    @keyframes animasilogin {
      0% {
        opacity: 0;
        transform: scale(0.98);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .page-wrapper {
      animation: animasilogin 300ms ease-in-out;
    }
  </style>
</head>

<body class="min-h-screen overflow-hidden flex flex-col font-tahoma relative bg-[#15171d]">

  <div class="page-wrapper relative flex flex-col min-h-screen">

    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#1b1d25] via-[#232734] to-[#15171d]"></div>

    <!-- Blur Glow -->
    <div class="absolute top-[-120px] left-[-120px] w-[400px] h-[400px] bg-cyan-500/10 blur-3xl rounded-full"></div>
    <div class="absolute bottom-[-120px] right-[-120px] w-[400px] h-[400px] bg-blue-500/10 blur-3xl rounded-full"></div>

    <!-- Grid Pattern -->
    <div class="absolute inset-0 opacity-[0.05]">
      <div class="w-full h-full bg-[linear-gradient(rgba(255,255,255,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.08)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
    </div>

    <!-- Tombol Exit -->
    <div class="relative z-10 p-4">
      <button onclick="history.back()">
        <img
          src="/logindark/logoexit.png"
          width="40"
          height="40"
          class="hover:scale-110 transition duration-200"
        >
      </button>
    </div>

    <!-- Login Box -->
    <div class="relative z-10 flex-1 flex items-center justify-center">

      <div class="bg-black/40 backdrop-blur-xl rounded-[30px] border border-white/20 px-10 py-10 flex flex-col items-center w-[425px] mb-12 shadow-[0_0_60px_rgba(255,255,255,0.30)]">

        <!-- Logo -->
        <img
          src="/logindark/logo.png"
          width="100"
          height="100"
          class="drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]"
        >

        <br>


        <form action="/login" method="POST" class="w-full">
          @csrf

          <!-- Username -->
          <div class="flex items-center gap-1 w-full mb-3">
            <div class="bg-[#2B2D34] border border-white/20 rounded-lg p-2">
              <img src="/daftarblack/logouser.png" width="28" height="28">
            </div>
            <input
              type="text"
              name="username"
              placeholder="username"
              class="rounded-md px-3 py-2 text-sm w-full border border-[#c8dff0]/50 bg-[#2B2D34]/80 text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition duration-200"
            >
          </div>

          <!-- Password -->
          <div class="flex items-center gap-1 w-full">
            <div class="bg-[#2B2D34] border border-white/20 rounded-lg p-2">
              <img src="/logindark/logopassword.png" width="28" height="28">
            </div>
            <div class="relative w-full">
              <input
                id="password"
                type="password"
                name="password"
                placeholder="password"
                class="rounded-md px-3 py-2 pr-9 text-sm w-full border border-[#c8dff0]/50 bg-[#2B2D34]/80 text-white outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 transition duration-200"
              >
              <span
                onclick="togglePassword()"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer opacity-70 hover:opacity-100 transition"
              >
                <img id="eyeIcon" src="/logindark/logomata.png" width="16" height="16">
              </span>
            </div>
          </div>

          <br>

          <!-- Login Button -->
          <div class="flex gap-3 w-40 mb-4 mx-auto">
            <button
              class="flex-1 py-2 rounded-md text-white text-sm font-tahoma bg-[#2a7db5] hover:bg-cyan-500 hover:shadow-[0_0_20px_rgba(34,211,238,0.5)] transition duration-300"
            >
              Login
            </button>
          </div>

        </form>
      </div>
    </div>

  </div><!-- end page-wrapper -->

  <script>
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

    background: 'rgba(24, 26, 32, 0.85)',
    color: '#e5e7eb',

    confirmButtonText: 'OK',

    customClass: {
        popup: 'rounded-[28px] border border-white/10 backdrop-blur-xl shadow-[0_0_40px_rgba(34,211,238,0.15)]',
        title: 'text-3xl font-bold text-white',
        htmlContainer: 'text-gray-300 text-base',
        confirmButton: 'rounded-xl px-6 py-2 text-sm font-semibold'
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
        rgba(0,0,0,0.65)
        backdrop-filter: blur(6px)
    `
})
</script>
@endif