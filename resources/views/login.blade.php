<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/lucide@latest"></script>

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

<body class="h-screen overflow-hidden font-sans relative bg-page text-textMain antialiased">

  <div class="absolute inset-0 bg-gradient-to-br from-surface via-page to-page z-0"></div>
  <div
    class="absolute top-[-120px] left-[-120px] w-[400px] h-[400px] bg-brand/10 blur-[100px] rounded-full pointer-events-none z-0">
  </div>
  <div
    class="absolute bottom-[-120px] right-[-120px] w-[400px] h-[400px] bg-brand/10 blur-[100px] rounded-full pointer-events-none z-0">
  </div>
  <div class="absolute inset-0 opacity-10 pointer-events-none z-0">
    <div
      class="w-full h-full bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px]">
    </div>
  </div>

  <div onclick="history.back()"
    class="absolute top-6 left-6 z-20 opacity-70 hover:opacity-100 hover:-translate-x-1 transition-all duration-200 cursor-pointer">
    <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
  </div>

  <div class="page-wrapper relative z-10 w-full h-full flex items-center justify-center">

    <div
      class="bg-surface/80 backdrop-blur-xl rounded-[24px] border border-borderSubtle px-10 py-12 flex flex-col items-center w-[400px] shadow-[0_0_50px_rgba(99,102,241,0.08)]">

      <img src="/logindark/logo.png" width="90" height="90" class="drop-shadow-[0_0_20px_rgba(99,102,241,0.2)] mb-6">

      <form action="/login" method="POST" class="w-full">
        @csrf

        <div class="flex items-center gap-2 w-full mb-4">
          <div class="bg-page border border-borderSubtle rounded-lg p-2.5 flex-shrink-0">
            <i data-lucide="user" class="w-[22px] h-[22px] opacity-80 text-textMuted"></i>
          </div>
          <input type="text" name="username" placeholder="Username"
            class="rounded-lg px-4 py-3 text-sm w-full border border-borderSubtle bg-page text-textMain placeholder-textMuted outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200">
        </div>

        <div class="flex items-center gap-2 w-full mb-3">
          <div class="bg-page border border-borderSubtle rounded-lg p-2.5 flex-shrink-0">
            <i data-lucide="lock" class="w-[22px] h-[22px] opacity-80 text-textMuted"></i>
          </div>
          <div class="relative w-full">
            <input id="password" type="password" name="password" placeholder="Password"
              class="rounded-lg px-4 py-3 pr-10 text-sm w-full border border-borderSubtle bg-page text-textMain placeholder-textMuted outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200">
            <span onclick="togglePassword()"
              class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100 transition-opacity">
              <span id="eyeIcon"><i data-lucide="eye-off" class="w-[18px] h-[18px] text-textMuted"></i></span>
            </span>
          </div>
        </div>

        <div class="flex justify-end w-full mb-6">
          <a href="#" onclick="showForgotPasswordAlert(event)"
            class="text-xs text-textMuted hover:text-brand transition-colors duration-200 font-medium tracking-wide">
            Lupa Password?
          </a>
        </div>

        <button type="submit"
          class="w-full py-3 rounded-lg text-white font-semibold tracking-wide bg-brand hover:bg-brandHover shadow-lg hover:shadow-brand/25 transition-all duration-300">
          Login
        </button>

      </form>
    </div>
  </div>
<script>
  // 1. Inisialisasi Icon Lucide saat halaman dimuat
  lucide.createIcons();

  // 2. Fungsi untuk menyembunyikan / menampilkan password (Show-Hide Password)
  function togglePassword() {
    const input = document.getElementById('password')
    const iconWrapper = document.getElementById('eyeIcon')

    if (input.type === 'password') {
      input.type = 'text'
      iconWrapper.innerHTML = '<i data-lucide="eye" class="w-[18px] h-[18px] text-textMuted"></i>'
    } else {
      input.type = 'password'
      iconWrapper.innerHTML = '<i data-lucide="eye-off" class="w-[18px] h-[18px] text-textMuted"></i>'
    }
    lucide.createIcons();
  }

  // 3. Fungsi Pop-up Informatif ketika tombol "Lupa Password?" diklik
  function showForgotPasswordAlert(event) {
      event.preventDefault(); // Menahan link agar tidak reload

      Swal.fire({
        title: 'Reset Password',
        text: 'Masukkan email akun Anda untuk menerima instruksi pemulihan:',
        input: 'email', // 👈 Mengubah pop-up menjadi form input email
        inputPlaceholder: 'nama@email.com',
        background: '#1a1b23', // bg-surface
        color: '#f1f3f9',      // text-main
        confirmButtonText: 'Kirim Link',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        customClass: {
          popup: 'rounded-[24px] border border-[#262833] backdrop-blur-xl shadow-[0_0_40px_rgba(99,102,241,0.15)]',
          title: 'text-2xl font-bold text-white',
          htmlContainer: 'text-[#787f99] text-sm mt-2',
          input: 'bg-page border border-[#262833] text-white rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#6366f1] focus:ring-1 focus:ring-[#6366f1]',
          confirmButton: 'rounded-lg px-6 py-2.5 text-sm font-semibold bg-[#6366f1] hover:bg-[#4f46e5] border-none text-white mr-2',
          cancelButton: 'rounded-lg px-6 py-2.5 text-sm font-semibold bg-transparent hover:bg-white/5 border border-borderSubtle text-textMuted'
        },
        buttonsStyling: false,
        backdrop: 'rgba(18, 19, 24, 0.75) backdrop-filter: blur(4px)',

        // Validasi input email sebelum dikirim
        inputValidator: (value) => {
          if (!value) {
            return 'Email tidak boleh kosong!';
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const emailUser = result.value;

          // Tampilkan animasi loading saat mengirim email
          Swal.fire({
            title: 'Mengirim...',
            text: 'Sedang memproses permintaan pemulihan akun Anda.',
            allowOutsideClick: false,
            background: '#1a1b23',
            color: '#f1f3f9',
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // 🚀 KIRIM DATA KE BACKEND LARAVEL VIA AJAX / FETCH
          fetch('/forgot-password', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}' // Mengambil token keamanan Laravel
            },
            body: JSON.stringify({ email: emailUser })
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Email Terkirim!',
                  text: 'Link reset password telah dikirim ke gmail Anda. Silakan cek kotak masuk atau spam.',
                  background: '#1a1b23',
                  color: '#f1f3f9',
                  confirmButtonText: 'Selesai',
                  customClass: {
                    confirmButton: 'rounded-lg px-8 py-2.5 text-sm font-semibold bg-[#6366f1] border-none'
                  },
                  buttonsStyling: false
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: data.message || 'Email tidak terdaftar di sistem kami.',
                  background: '#1a1b23',
                  color: '#f1f3f9',
                  confirmButtonText: 'Coba Lagi',
                  customClass: {
                    confirmButton: 'rounded-lg px-8 py-2.5 text-sm font-semibold bg-[#ef4444] border-none'
                  },
                  buttonsStyling: false
                });
              }
            })
            .catch(() => {
              Swal.fire({
                icon: 'error',
                title: 'Error Sistem',
                text: 'Terjadi kesalahan jaringan, silakan hubungi admin.',
                background: '#1a1b23',
                color: '#f1f3f9'
              });
            });
        }
      });
    }

  // 4. Logika Laravel Session Error (Otomatis dieksekusi jika login gagal)
  @if(session('error'))
    Swal.fire({
      title: 'Login Gagal',
      text: "{{ session('error') }}",
      icon: 'error',
      background: '#1a1b23', // bg-surface
      color: '#f1f3f9',      // text-main
      confirmButtonText: 'OK',
      customClass: {
        popup: 'rounded-[24px] border border-[#262833] backdrop-blur-xl shadow-[0_0_40px_rgba(239,68,68,0.15)]',
        title: 'text-2xl font-bold text-white',
        htmlContainer: 'text-[#787f99] text-sm', // text-muted
        confirmButton: 'rounded-lg px-8 py-2.5 text-sm font-semibold bg-[#ef4444] hover:bg-[#dc2626] border-none' // alert-critical
      },
      buttonsStyling: false,
      showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp animate__faster'
      },
      backdrop: 'rgba(18, 19, 24, 0.75) backdrop-filter: blur(4px)'
    });
  @endif
</script>

</body>

</html>