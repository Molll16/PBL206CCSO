<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Halaman Login - Central Cyber Security Office</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
    @keyframes animasidaftar {
      0% {
        opacity: 0;
      }
      100% {
        opacity: 1;
      }
    }

    html {
      animation: animasidaftar 300ms ease-in-out;
      scroll-behavior: smooth;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col font-tahoma" style="background: #2B2D34;">

  <!-- Tombol Exit -->
  <div class="p-2">
    <button onclick="history.back()">
      <img src="/logindark/logoexit.png" width="36" height="36">
    </button>
  </div>

  <!-- Kotak -->
  <div class="flex-1 flex items-center justify-center">
    <div class="bg-[#212121] rounded-2xl border-4 outline-white-4 px-8 py-4 flex flex-col items-center w-80 mb-12">

      <!-- Logo -->
      <img src="/logindark/logo.png" width="70" height="70" class="mb-2">

      <!-- Full Name -->
      <div class="flex items-center gap-1 w-full mb-2">
        <div class="bg-[#2B2D34] border-2 outline-white rounded-lg p-1.5">
          <img src="/daftarblack/logouser.png" width="22" height="22">
        </div>
        <input type="text" placeholder="Full Name"
          class="rounded-md px-3 py-1.5 text-xs w-full"
          style="border: 1.5px solid #c8dff0; background:#2B2D34; color:#374151;">
      </div>

      <!-- Email -->
      <div class="flex items-center gap-1 w-full mb-2">
        <div class="bg-[#2B2D34] border-2 outline-white rounded-lg p-1.5">
          <img src="/daftarblack/logoemail.png" width="22" height="22">
        </div>
        <input type="text" placeholder="Email"
          class="rounded-md px-3 py-1.5 text-xs w-full"
          style="border: 1.5px solid #c8dff0; background:#2B2D34; color:#374151;">
      </div>

      <!-- No HP -->
      <div class="flex items-center gap-1 w-full mb-2">
        <div class="bg-[#2B2D34] border-2 outline-white rounded-lg p-1.5">
          <img src="/daftarblack/logotelp.png" width="22" height="22">
        </div>
        <input type="text" placeholder="Personal Phone Number"
          class="rounded-md px-3 py-1.5 text-xs w-full"
          style="border: 1.5px solid #c8dff0; background:#2B2D34; color:#374151;">
      </div>

      <!-- Company Name -->
      <div class="flex items-center gap-1 w-full mb-2">
        <div class="bg-[#2B2D34] border-2 outline-white rounded-lg p-1.5">
          <img src="/daftarblack/logocompany.png" width="22" height="22">
        </div>
        <input type="text" placeholder="Company Name"
          class="rounded-md px-3 py-1.5 text-xs w-full"
          style="border: 1.5px solid #c8dff0; background:#2B2D34; color:#374151;">
      </div>

      <!-- Checkbox Privacy Policy -->
      <div class="flex items-center gap-2 w-full mb-3">
        <input type="checkbox" id="privacy" class="w-3.5 h-3.5 cursor-pointer">
        <label for="privacy" class="text-xs text-white">
          I agree to CCSO Agent <a href="#" class="text-[#3d9de8] hover:underline">privacy policy.</a>
        </label>
      </div>

      <!-- Tombol Login -->
      <div class="flex gap-3 w-28 mb-3">
        <button class="flex-1 py-1.5 rounded-md text-white text-sm font-tahoma" style="background:#2a7db5;">Sign Up</button>
      </div>

      <!-- Sign Up -->
      <p class="text-xs text-white">
        Don't you have an account?
        <a href="{{ route('login') }}" class="text-[#3d9de8] hover:underline">Login</a>
      </p>

    </div>
  </div>

</body>
</html>