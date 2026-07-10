<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - SIEM</title>

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

    <div class="page-wrapper relative z-10 w-full h-full flex items-center justify-center">

        <div
            class="bg-surface/80 backdrop-blur-xl rounded-[24px] border border-borderSubtle px-10 py-12 flex flex-col items-center w-[400px] shadow-[0_0_50px_rgba(99,102,241,0.08)]">

            <h2 class="text-xl font-bold text-white mb-2 tracking-wide">Buat Password Baru</h2>
            <p class="text-xs text-textMuted text-center mb-8">Amankan kembali akun dashboard SIEM Anda dengan kombinasi
                sandi baru.</p>

            <form action="/reset-password" method="POST" class="w-full">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="flex items-center gap-2 w-full mb-4">
                    <div class="bg-page border border-borderSubtle rounded-lg p-2.5 flex-shrink-0">
                        <i data-lucide="lock" class="w-[22px] h-[22px] opacity-80 text-textMuted"></i>
                    </div>
                    <input type="password" name="password" placeholder="Password Baru (min 6 karakter)"
                        class="rounded-lg px-4 py-3 text-sm w-full border border-borderSubtle bg-page text-textMain placeholder-textMuted outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200"
                        required>
                </div>

                <div class="flex items-center gap-2 w-full mb-8">
                    <div class="bg-page border border-borderSubtle rounded-lg p-2.5 flex-shrink-0">
                        <i data-lucide="check-square" class="w-[22px] h-[22px] opacity-80 text-textMuted"></i>
                    </div>
                    <input type="password" name="password_confirmation" placeholder="Ulangi Password Baru"
                        class="rounded-lg px-4 py-3 text-sm w-full border border-borderSubtle bg-page text-textMain placeholder-textMuted outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200"
                        required>
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-lg text-white font-semibold tracking-wide bg-brand hover:bg-brandHover shadow-lg hover:shadow-brand/25 transition-all duration-300">
                    Simpan Password
                </button>

            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

    @if($errors->any())
        <script>
            Swal.fire({
                title: 'Validasi Gagal',
                text: "{{ $errors->first() }}",
                icon: 'error',
                background: '#1a1b23',
                color: '#f1f3f9',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-[24px] border border-[#262833] backdrop-blur-xl shadow-[0_0_40px_rgba(239,68,68,0.15)]',
                    title: 'text-2xl font-bold text-white',
                    htmlContainer: 'text-[#787f99] text-sm',
                    confirmButton: 'rounded-lg px-8 py-2.5 text-sm font-semibold bg-[#ef4444] border-none'
                },
                buttonsStyling: false,
                backdrop: 'rgba(18, 19, 24, 0.75) backdrop-filter: blur(4px)'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Ganti Password Gagal',
                text: "{{ session('error') }}",
                icon: 'error',
                background: '#1a1b23',
                color: '#f1f3f9',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-[24px] border border-[#262833] backdrop-blur-xl shadow-[0_0_40px_rgba(239,68,68,0.15)]',
                    title: 'text-2xl font-bold text-white',
                    htmlContainer: 'text-[#787f99] text-sm',
                    confirmButton: 'rounded-lg px-8 py-2.5 text-sm font-semibold bg-[#ef4444] border-none'
                },
                buttonsStyling: false,
                backdrop: 'rgba(18, 19, 24, 0.75) backdrop-filter: blur(4px)'
            });
        </script>
    @endif

</body>

</html>