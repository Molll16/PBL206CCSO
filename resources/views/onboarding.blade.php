<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>landing page- Central Cyber Security Office</title>
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
</head>

<body class="min-h-screen flex flex-col bg-[#2B2D34] text-[white] font-tahoma">

    <!-- navbar -->
  <header class="bg-[#212121] border-b-2 border-white">
    <div class="flex items-center h-16">

      <!-- Hamburger -->
     <button class="flex items-center justify-center w-16 h-16 border-r-4 border-white ">
        <div class="w-8 h-8 border-2 border-white rounded-lg flex items-center justify-center">
            <img src="/ob/sidebar.png" class="w-3 h-3 ">
        </div>
    </button>

      <!-- Home -->
      <button class="flex items-center justify-center w-16 h-16 ">
        <img src="/ob/home.png" class="w-8">

      </button>

      <!-- Logo -->
      <div class="flex items-center gap-3 px-2">
        <img src="/ob/logo.png" class="w-6">
        <div class="font-tahoma">
          <p class="text-xs tracking-wide">Central Cyber</p>
          <p class="text-xs tracking-wide">Security Office</p>
        </div>
      </div>

      <div class="flex-1"></div>

      <!-- manage/+ -->
      <div class="flex items-center gap-3 pr-3">
        <button class="flex items-center gap-2 border border-white rounded-md px-4 py-2 text-sm ">
          Manage
          <img src="/ob/arrow.png" class="w-3">
        </button>
        <button class="flex items-center justify-center w-9 h-9 border border-white rounded-md text-lg">
          +
        </button>
      </div>

    </div>
  </header>

    <!-- main -->
  <main class="py-4 px-4  ">

    <!-- ── Password Recreate ── -->
    <section class="mb-5">
      <h2 class="text-m font-tahoma border-b-2 border-white pb-1 inline-block mb-4">
        Password Recreate
      </h2>
      <div class="space-y-4">

        <!-- Current Password -->
       <div>
  <p class="text-sm font-tahoma mb-2">Your current password:</p>

  <div class="flex items-center gap-2">

    <!-- ICON BOX (terpisah) -->
    <div class="flex items-center justify-center w-11 h-11 border-2 border-white rounded-lg">
      <img src="/ob/lock.png" class="w-5 h-5">
    </div>
    <!-- INPUT BOX -->
    <div class="flex items-center border-2 border-white rounded-lg w-64 ">
      <input
        type="password"
        placeholder="Current Password"
        class="flex-1 bg-transparent outline-none text-sm text-[#8a939e] placeholder-[#8a939e] px-3 py-2.5">

        <button class="px-3 text-[#8a939e] hover:text-[#e2e6ec] transition">
            <div>
                <img src="/ob/eye.png">
        </button>
            </div>

  </div>
</div>

        <!-- New Password -->
        <div>
          <p class="text-sm font-tahoma mb-2">Your new password:</p>
          <div class="flex items-center gap-2">

    <!-- ICON BOX (terpisah) -->
    <div class="flex items-center justify-center w-11 h-11 border-2 border-white rounded-lg">
      <img src="/ob/lock.png" class="w-5 h-5">
    </div>
    <!-- INPUT BOX -->
    <div class="flex items-center border-2 border-white rounded-lg w-64  ">
      <input
        type="password"
        placeholder="New Password"
        class="flex-1 bg-transparent outline-none text-sm text-[#8a939e] placeholder-[#8a939e] px-3 py-2.5">

        <button class="px-3 text-[#8a939e] hover:text-[#e2e6ec] transition">
            <div>
                <img src="/ob/eye.png">
        </button>
            </div>

  </div>
</div>

        <!-- Confirm Password -->
      <div class="flex items-center gap-2">

    <!-- ICON BOX (terpisah) -->
    <div class="flex items-center justify-center w-11 h-11 border-2 border-white rounded-lg">
      <img src="/ob/lock.png" class="w-5 h-5">
    </div>
    <!-- INPUT BOX -->
    <div class="flex items-center border-2 border-white rounded-lg w-64 ">
      <input
        type="password"
        placeholder="Confirm New Password"
        class="flex-1 bg-transparent outline-none text-sm text-[#8a939e] placeholder-[#8a939e] px-3 py-2.5">

        <button class="px-3 text-[#8a939e] hover:text-[#e2e6ec] transition">
            <div>
                <img src="/ob/eye.png">
        </button>
            </div>

  </div>
</div>

      </div>
    </section>


    <!-- ── Deploy New Agent ── -->
    <section>

      <h2 class="text-xl font-tahoma border-b-2 border-white pb-1 inline-block mb-6">
        Deploy New Agent
      </h2>

      <!-- ── Step 1: Select Package ── -->
      <div class="flex gap-3">

        <!-- Timeline -->
        <div class="flex flex-col items-center shrink-0 pt-0.5">
          <div class="w-7 h-7 rounded-full bg-[#2e8bff] flex items-center justify-center shrink-0">
            <!-- [ isi icon checkmark ] -->
            <div class="w-3 h-3 bg-white rounded-sm"></div>
          </div>
          <div class="w-0.5 bg-[#2e8bff] flex-1 my-2"></div>
        </div>

        <!-- Content -->
        <div class="flex-1 pb-8">
          <p class="text-base font-tahoma mb-2">
            Select the package to download and install on your system:
          </p>

         <!-- OS Cards -->
<div class="flex gap-3 mb-4 w-1/2 h-32">

  <!-- Linux -->
  <div class="flex-1 px-2 py-3 border-2  border-white cursor-pointer ">
    <div class="flex items-center justify-center gap-2 pb-2 border-b border-[#A4A4A4] mb-2">
      <img src="/ob/linux.png" class="w-5 h-5">
      <p class="font-tahoma text-xs tracking-wide">LINUX</p>
    </div>
    <div class="grid grid-cols-2 gap-y-1 gap-x-2">
      <label class="flex items-center gap-1 text-[10px]  cursor-pointer">
        <input type="radio" name="linux"  /> RPM amd64
      </label>
      <label class="flex items-center gap-1 text-[10px] cursor-pointer">
        <input type="radio" name="linux" /> RPM aarch64
      </label>
      <label class="flex items-center gap-1 text-[10px] cursor-pointer">
        <input class="bg-[#a4a4a4]" type="radio" name="linux" /> DEB amd64
      </label>
      <label class="flex items-center gap-1 text-[10px] cursor-pointer">
        <input type="radio" name="linux"  /> DEB aarch64
      </label>
    </div>
  </div>

  <!-- Windows -->
  <div class="flex-1 bg-[#22262c] border border-[#2e333b] rounded-lg px-2 py-3 hover:border-[#2e8bff] transition-colors cursor-pointer">
    <div class="flex items-center gap-2 pb-2 border-b border-[#2e333b] mb-2 text-xs font-semibold">
      <div class="w-5 h-5 bg-[#3a4049] rounded"></div>
      WINDOWS
    </div>
    <div class="flex justify-center pt-1">
      <label class="flex items-center gap-1 text-[10px] text-[#8a939e] cursor-pointer">
        <input type="radio" name="windows" class="accent-[#2e8bff]" checked /> MSI 32/64 bits
      </label>
    </div>
  </div>

  <!-- MacOS -->
  <div class="flex-1 bg-[#22262c] border border-[#2e333b] rounded-lg px-2 py-3 hover:border-[#2e8bff] transition-colors cursor-pointer">
    <div class="flex items-center gap-2 pb-2 border-b border-[#2e333b] mb-2 text-xs font-semibold">
      <div class="w-5 h-5 bg-[#3a4049] rounded"></div>
      MacOS
    </div>
    <div class="space-y-1">
      <label class="flex items-center gap-1 text-[10px] text-[#8a939e] cursor-pointer">
        <input type="radio" name="macos" class="accent-[#2e8bff]" /> Intel
      </label>
      <label class="flex items-center gap-1 text-[10px] text-[#8a939e] cursor-pointer">
        <input type="radio" name="macos" class="accent-[#2e8bff]" /> Apple Silicon
      </label>
    </div>
  </div>

</div>

          <!-- Info Banner -->
          <div class="flex items-start gap-2 bg-blue-950/30 border border-blue-800/40 rounded-md px-4 py-2.5 text-xs text-[#8a939e]">
            <!-- [ isi icon info ] -->
            <div class="w-4 h-4 bg-[#2e8bff] rounded-full shrink-0 mt-0.5"></div>
            <p>
              For additional systems and architectures, please check our
              <a href="#" class="text-[#2e8bff] underline">Documentation</a>.
            </p>
          </div>
        </div>

      </div>


      <!-- ── Step 2: Server Address ── -->
      <div class="flex gap-5">

        <!-- Timeline -->
        <div class="flex flex-col items-center shrink-0 pt-0.5">
          <div class="w-7 h-7 rounded-full border-2 border-[#3a4049] flex items-center justify-center text-xs text-[#3a4049] font-semibold shrink-0">
            2
          </div>
          <div class="w-0.5 bg-[#2e333b] flex-1 my-2"></div>
        </div>

        <!-- Content -->
        <div class="flex-1 pb-8">
          <p class="text-base font-semibold mb-1">Server address:</p>
          <p class="text-sm text-[#8a939e] mb-4">
            This is the address the agent uses to communicate with the server.
            Enter an IP address or a fully qualified domain name (FQDN).
          </p>

          <label class="block text-sm font-medium mb-2">Assign a server address</label>
          <input
            type="text"
            placeholder="12.3.4.567.890"
            class="bg-[#22262c] border border-[#2e333b] rounded-md px-3 py-2.5 text-sm text-[#8a939e] placeholder-[#3a4049] outline-none w-full max-w-xs focus:border-[#2e8bff] transition-colors mb-3"
          />

          <!-- Toggle -->
          <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative w-9 h-5">
              <input type="checkbox" checked class="sr-only peer" />
              <div class="w-9 h-5 bg-[#2e8bff] rounded-full transition-colors"></div>
              <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full peer-checked:translate-x-4 transition-transform"></div>
            </div>
            <span class="text-sm text-[#8a939e]">Remember server address</span>
          </label>
        </div>

      </div>


      <!-- ── Step 3: Optional Settings ── -->
      <div class="flex gap-5">

        <!-- Timeline (no line below — last step) -->
        <div class="flex flex-col items-center shrink-0 pt-0.5">
          <div class="w-7 h-7 rounded-full border-2 border-[#3a4049] flex items-center justify-center text-xs text-[#3a4049] font-semibold shrink-0">
            3
          </div>
        </div>

        <!-- Content -->
        <div class="flex-1 pb-4">
          <p class="text-base font-semibold mb-1">Optional settings:</p>
          <p class="text-sm text-[#8a939e] mb-4">
            By default, the deployment uses the hostname as the agent name.
            Optionally, you can use a different agent name in the field below.
          </p>

          <label class="block text-sm font-medium mb-2">Assign an agent name:</label>
          <input
            type="text"
            placeholder="John Doe"
            class="bg-[#22262c] border border-[#2e333b] rounded-md px-3 py-2.5 text-sm text-[#8a939e] placeholder-[#3a4049] outline-none w-full max-w-xs focus:border-[#2e8bff] transition-colors mb-4"
          />

          <!-- Warning Banner -->
          <div class="flex items-start gap-2 bg-yellow-900/20 border border-yellow-700/40 rounded-md px-4 py-2.5 text-xs text-yellow-600 max-w-xl">
            <!-- [ isi icon warning ] -->
            <div class="w-4 h-4 bg-yellow-600 rounded-full shrink-0 mt-0.5"></div>
            <p>The agent name must be unique. It can't be changed once the agent has been enrolled.</p>
          </div>
        </div>

      </div>

    </section>
  </main>


  <!-- ══════════════════════════════════════
       FOOTER
  ══════════════════════════════════════ -->
  <footer class="border-t border-[#2e333b] bg-[#22262c] mt-auto">
    <div class="flex flex-wrap items-center gap-6 px-8 py-5">

      <!-- Logo + Copyright -->
      <div class="flex items-center gap-3">
        <!-- [ isi logo CCSO ] -->
        <div class="w-7 h-7 bg-[#3a4049] rounded"></div>
        <span class="text-sm text-[#8a939e]">© 2026 CCSO, Inc.</span>
      </div>

      <div class="h-5 w-px bg-[#2e333b] hidden sm:block"></div>

      <a href="#" class="text-sm text-[#8a939e] hover:text-[#e2e6ec] transition-colors">Contact Us</a>

      <div class="h-5 w-px bg-[#2e333b] hidden sm:block"></div>

      <!-- Phone -->
      <div class="flex items-center gap-2 text-sm text-[#8a939e]">
        <!-- [ isi icon phone ] -->
        <div class="w-4 h-4 bg-[#3a4049] rounded shrink-0"></div>
        +62 1234567890
      </div>

      <!-- Social + Subscribe -->
      <div class="flex items-center gap-4 ml-auto">
        <!-- [ isi icon TikTok ] -->
        <a href="#" class="w-5 h-5 bg-[#3a4049] rounded hover:bg-[#2e8bff] transition-colors block"></a>
        <!-- [ isi icon Instagram ] -->
        <a href="#" class="w-5 h-5 bg-[#3a4049] rounded hover:bg-[#2e8bff] transition-colors block"></a>
        <!-- [ isi icon WhatsApp ] -->
        <a href="#" class="w-5 h-5 bg-[#3a4049] rounded hover:bg-[#2e8bff] transition-colors block"></a>
        <!-- [ isi icon Email ] -->
        <a href="#" class="w-5 h-5 bg-[#3a4049] rounded hover:bg-[#2e8bff] transition-colors block"></a>

        <!-- Subscribe -->
        <div class="flex items-center border border-[#2e333b] rounded-md overflow-hidden">
          <input
            type="email"
            placeholder="Sent to our Email..."
            class="bg-transparent text-sm text-[#8a939e] placeholder-[#3a4049] px-3 py-2 outline-none w-44"
          />
          <button class="bg-[#2e8bff] hover:bg-blue-700 transition-colors px-3 py-2 flex items-center justify-center">
            <!-- [ isi icon arrow right ] -->
            <div class="w-4 h-4 bg-white rounded-sm"></div>
          </button>
        </div>
      </div>

    </div>
  </footer>

</body>
</html>