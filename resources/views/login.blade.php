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
    @keyframes animasilogin {
      0% {
        opacity: 0;
      }
      100% {
        opacity: 1;
      }
    }

    html {
      animation : animasilogin 300ms ease-in-out;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col font-tahoma" style="background: #2B2D34;">

 <!-- Tombol Exit -->
<div class="p-4">
  <button onclick="history.back()">
    <img src="/logindark/logoexit.png" width="40" height="40">
  </button>
</div>
  <!--kotak-->
  <div class="flex-1 flex items-center justify-center">

    <div class="bg-[#212121] rounded-2xl border-4 outline-white-4 px-10 py-10 flex flex-col items-center w-96 mb-12">

      <!-- Logo -->
      <img src="/logindark/logo.png" width="100" height="100">
      <br>

      <!-- Email -->
      <div class="flex items-center gap-1 w-full mb-3">
        <div class="bg-[#2B2D34] border-2 outline-white rounded-lg p-2">
          <img src="/logindark/logoemail.png" width="28" height="28">
        </div>
        <input type="text" placeholder="email"
          class="rounded-md px-3 py-2 text-sm w-full"
          style="border: 1.5px solid #c8dff0; background:#2B2D34; color:#374151;">
      </div>

      <!-- Input password -->
      <div class="flex items-center gap-1 w-full">
        <div class="bg-[#2B2D34] border-2 outline-white rounded-lg p-2">
          <img src="/logindark/logopassword.png" width="28" height="28">
        </div>
        <div class="relative w-full ">
          <input id="password" type="password" placeholder="password"
            class="rounded-md px-3 py-2 pr-9 text-sm w-full"
            style="border: 1.5px solid #c8dff0; background:#2B2D34; color:#374151;">
          <span onclick="togglePassword()" class="absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer">
            <img id="eyeIcon" src="/logindark/logomata.png" width="16" height="16">
          </span>
        </div>
      </div>

      <br>

      <!-- Tombol Login -->
      <div class="flex gap-3 w-32 mb-4">
        <button class="flex-1 py-2 rounded-md text-white text-sm font-tahoma" style="background:#2a7db5;">Login</button>
      </div>

      <!-- Sign Up -->
      <p class="text-sm text-white">
        Don't you have an account?
        <a href="{{ route('daftar') }}" class="text-[#3d9de8] hover:underline">Sign Up</a>
      </p>

    </div>

  </div>

</body>

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
</html>