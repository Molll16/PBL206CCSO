<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Overview</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @vite('resources/js/app.js')
</head>
<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('Customer.components.header')

  <main class="flex-1 flex flex-col">
    <div class="p-2 underline underline-offset-8">
      <a href="{{ route('dashboard-customer') }}" class="text-white">Password Recreate</a>
    </div>

    <div class="flex-1 flex items-center justify-center">
      <div class="bg-[#212121] rounded-2xl border-4 border-white px-10 py-6 flex flex-col items-center w-96 mb-12">

        <img src="/logindark/logo.png" width="100" height="100" class="mb-4" alt="Logo">

        {{-- Error Messages --}}
        @if($errors->any())
          <div class="w-full mb-3 bg-red-500/10 border border-red-500 text-red-300 px-4 py-2 rounded text-sm">
            {{ $errors->first() }}
          </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
          <div class="w-full mb-3 bg-green-500/10 border border-green-500 text-green-300 px-4 py-2 rounded text-sm">
            {{ session('success') }}
          </div>
        @endif

        {{-- TAMBAH FORM --}}
        <form method="POST" action="{{ route('changepw.update') }}" class="w-full space-y-3">
          @csrf

          <div>
            <h1 class="font-bold text-sm mb-1">Your Current Password:</h1>
            <div class="flex items-center gap-1">
              <div class="bg-[#2B2D34] border-2 border-white rounded-lg p-2">
                <img src="/logindark/logopassword.png" width="28" height="28" alt="">
              </div>
              <div class="relative w-full">
                <input id="current_pwd" type="password" name="current_password"
                  placeholder="Current Password"
                  class="w-full rounded-md px-3 py-2 pr-9 text-sm bg-[#2B2D34] text-gray-200 border-[1.5px] border-[#c8dff0] focus:outline-none">
                <span onclick="togglePassword('current_pwd', 'eye1')" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
                  <img id="eye1" src="/logindark/logomata.png" width="16" height="16" alt="">
                </span>
              </div>
            </div>
          </div>

          <div>
            <h1 class="font-bold text-sm mb-1">Your New Password:</h1>
            <div class="flex items-center gap-1">
              <div class="bg-[#2B2D34] border-2 border-white rounded-lg p-2">
                <img src="/logindark/logopassword.png" width="28" height="28" alt="">
              </div>
              <div class="relative w-full">
                <input id="new_pwd" type="password" name="new_password"
                  placeholder="New Password"
                  class="w-full rounded-md px-3 py-2 pr-9 text-sm bg-[#2B2D34] text-gray-200 border-[1.5px] border-[#c8dff0] focus:outline-none">
                <span onclick="togglePassword('new_pwd', 'eye2')" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
                  <img id="eye2" src="/logindark/logomata.png" width="16" height="16" alt="">
                </span>
              </div>
            </div>
          </div>

          <div>
            <div class="flex items-center gap-1">
              <div class="bg-[#2B2D34] border-2 border-white rounded-lg p-2">
                <img src="/logindark/logopassword.png" width="28" height="28" alt="">
              </div>
              <div class="relative w-full">
                <input id="confirm_pwd" type="password" name="new_password_confirmation"
                  placeholder="Confirm New Password"
                  class="w-full rounded-md px-3 py-2 pr-9 text-sm bg-[#2B2D34] text-gray-200 border-[1.5px] border-[#c8dff0] focus:outline-none">
                <span onclick="togglePassword('confirm_pwd', 'eye3')" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
                  <img id="eye3" src="/logindark/logomata.png" width="16" height="16" alt="">
                </span>
              </div>
            </div>
          </div>

          <div class="flex justify-center">
            <button type="submit"
              class="mt-6 w-32 py-2 rounded-md text-white text-sm bg-[#2a7db5] hover:bg-blue-600 transition-colors">
              Update
            </button>
          </div>

        </form>
        {{-- AKHIR FORM --}}

      </div>
    </div>
  </main>

  <footer class="bg-[#212121] px-10 py-4 border-t-2 border-gray-600">
    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
      <img src="/lp/logo.png" class="h-10" alt="">
      <p class="text-white">© 2026 CCSO, Inc.</p>
      <img src="/lp/garis.png" class="h-5" alt="">
      <p class="text-white">Contact Us</p>
      <img src="/lp/garis.png" class="h-5" alt="">

      <div class="flex items-center gap-2">
        <img src="/lp/telp.png" class="h-5" alt="">
        <p>+62 1234567890</p>
      </div>

      <div class="flex items-center gap-6 ml-2">
        <img src="/lp/tt.png" class="h-5 cursor-pointer" alt="">
        <img src="/lp/ig.png" class="h-5 cursor-pointer" alt="">
        <img src="/lp/wa.png" class="h-5 cursor-pointer" alt="">
        <img src="/lp/email.png" class="h-5 cursor-pointer" alt="">
      </div>

      <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
        <input type="email" placeholder="Sent to our Email..." class="px-3 py-1 text-sm w-56 text-gray-800 focus:outline-none">
        <button class="bg-blue-500 px-3 py-1 text-white hover:bg-blue-600">›</button>
      </div>
    </div>
  </footer>

  <script>
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);

      if (input.type === 'password') {
        input.type = 'text';
        icon.src = '/logindark/logomataopen.png';
      } else {
        input.type = 'password';
        icon.src = '/logindark/logomata.png';
      }
    }
  </script>
</body>
</html>