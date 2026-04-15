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

  <!-- NAVBAR -->
  <header class="bg-[#212121] border-b-2 border-white">
    <div class="flex items-center h-16">

      <!-- Hamburger -->
      <button class="flex items-center justify-center w-16 h-16 border-r-4 border-white">
        <div class="w-8 h-8 border-2 border-white rounded-lg flex items-center justify-center">
          <img src="/ob/sidebar.png" class="w-3 h-3">
        </div>
      </button>

      <!-- Home -->
      <button class="flex items-center justify-center w-16 h-16">
        <img src="/ob/home.png" class="w-8">
      </button>

      <!-- Logo & Brand -->
      <div class="flex items-center gap-3 px-2">
        <img src="/ob/logo.png" class="w-6">
        <div class="font-tahoma">
          <p class="text-xs tracking-wide">Central Cyber</p>
          <p class="text-xs tracking-wide">Security Office</p>
        </div>
      </div>

      <div class="flex-1"></div>

      <!-- Manage & Plus -->
      <div class="flex items-center gap-3 pr-3">
        <button class="flex items-center gap-2 border border-white rounded-md px-4 py-2 text-sm">
          Manage
          <img src="/ob/arrow.png" class="w-3">
        </button>
        <button class="flex items-center justify-center w-9 h-9 border border-white rounded-md text-lg">
          +
        </button>
      </div>

    </div>
  </header>

  <!-- MAIN -->
  <main class="mx-4 font-tahoma">

    <!-- Welcome -->
    <p class="mb-5">Welcome back, <span class="text-[#3282B8] font-tahoma">USER</span></p>

    <!-- Stats -->
    <div class="grid grid-cols-4 text-center mb-8">
      <div>
        <p class="mb-1">Agent ID</p>
        <p class="text-xl font-bold">01</p>
      </div>
      <div>
        <p class="mb-1">Total Alert</p>
        <p class="text-xl font-bold">0</p>
      </div>
      <div>
        <p class="mb-1">Company</p>
        <p class="text-xl font-bold">0</p>
      </div>
      <div>
        <p class="mb-1">Your Dashboard</p>
        <p class="text-xl font-bold">0</p>
      </div>
    </div>

    <!-- SECURITY INFORMATION MANAGEMENT -->
    <div class="border-2 rounded-sm px-5 pt-8 pb-8 mb-14 relative">

      <!-- Judul Section -->
      <div class="absolute -top-3 left-0 right-0 flex items-center gap-4 px-5">
        <div class="mx-[150px] bg-[#2B2D34]"></div>
        <span class="text-xs tracking-widest uppercase bg-[#2B2D34] px-20">
          Security Information Management
        </span>
      </div>

      <div class="grid grid-cols-2 gap-16">
        <!-- Security Events -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/event.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">Security Events</p>
            <p class="text-xs">Detect, record, and help prevent security threats.</p>
          </div>
        </div>

        <!-- Integrity Monitoring -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/monitoring.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">Integrity Monitoring</p>
            <p class="text-xs">Detect unauthorized changes to keep data intact and secure.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- AUDITING AND POLICY MONITORING -->
    <div class="border-2 rounded-sm px-5 pt-8 pb-8 mb-14 relative">

      <!-- Judul Section -->
      <div class="absolute -top-3 left-0 right-0 flex items-center gap-4 px-5">
        <div class="mx-[150px] bg-[#2B2D34]"></div>
        <span class="text-xs tracking-widest uppercase bg-[#2B2D34] px-20">
          Auditing and Policy Monitoring
        </span>
      </div>

      <div class="grid grid-cols-2 gap-16">
        <!-- Policy Monitoring -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/policy.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">Policy Monitoring</p>
            <p class="text-xs">Check system compliance with applicable policies.</p>
          </div>
        </div>

        <!-- System Auditing -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/auditing.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">System Auditing</p>
            <p class="text-xs">Evaluate and trace system activity.</p>
          </div>
        </div>
      </div>

      <!-- Security Configuration Assessment -->
      <div class="my-4 w-[550px] border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
        <div class="w-16 h-14 flex items-center justify-center">
          <img src="/db/security.png">
        </div>
        <div>
          <p class="font-bold text-sm mb-3">Security Configuration Assessment</p>
          <p class="text-xs">Check for configuration weaknesses to better protect the system.</p>
        </div>
      </div>

    </div>

    <!-- THREAT DETECTION AND RESPONSE -->
    <div class="border-2 rounded-sm px-5 pt-8 pb-8 mb-14 relative">

      <!-- Judul Section -->
      <div class="absolute -top-3 left-0 right-0 flex items-center gap-4 px-5">
        <div class="mx-[150px] bg-[#2B2D34]"></div>
        <span class="text-xs tracking-widest uppercase bg-[#2B2D34] px-20">
          Threat Detection and Response
        </span>
      </div>

      <div class="grid grid-cols-2 gap-16">
        <!-- Vulnerabilities -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/vulner.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">Vulnerabilities</p>
            <p class="text-xs">Identify weak points so they can be fixed immediately.</p>
          </div>
        </div>

        <!-- MITRE ATT&CK -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/mitre.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">MITRE ATT&amp;CK</p>
            <p class="text-xs">Understand attack patterns to make security defenses more effective.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- REGULATORY COMPLIANCE -->
    <div class="border-2 rounded-sm px-5 pt-8 pb-8 mb-14 relative">

      <!-- Judul Section -->
      <div class="absolute -top-3 left-0 right-0 flex items-center gap-4 px-5">
        <div class="mx-[150px] bg-[#2B2D34]"></div>
        <span class="text-xs tracking-widest uppercase bg-[#2B2D34] px-28">
          Regulatory Compliance
        </span>
      </div>

      <div class="grid grid-cols-2 gap-10">
        <!-- PCI DSS -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/pci.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">PCI DSS</p>
            <p class="text-xs">Maintaining credit/debit card data security and payment compliance.</p>
          </div>
        </div>

        <!-- NIST 800-53 -->
        <div class="border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/nist.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">NIST 800-53</p>
            <p class="text-xs">Protect systems and data with structured security standards.</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-10">
        <!-- TSC -->
        <div class="my-4 border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/tsc.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">TSC</p>
            <p class="text-xs">Ensure systems are secure, available, and data is protected.</p>
          </div>
        </div>

        <!-- GDPR -->
        <div class="my-4 w-[545px] border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/gdpr.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">GDPR</p>
            <p class="text-xs">Ensure privacy and security of personal data</p>
          </div>
        </div>

        <!-- HIPAA -->
        <div class="-my-10 mb-2 border-2 rounded-sm p-6 flex gap-3 hover:bg-[#3a3d47] transition cursor-pointer">
          <div class="w-16 h-14 flex items-center justify-center">
            <img src="/db/hipaa.png">
          </div>
          <div>
            <p class="font-bold text-sm mb-3">HIPAA</p>
            <p class="text-xs">Maintain the privacy and security of health information.</p>
          </div>
        </div>
      </div>

    </div>

  </main>

  <!-- FOOTER -->
  <footer class="bg-[#212121] px-10 py-4 border-t-2">
    <div id="footer">
      <div class="mt-2 px-1 flex flex-wrap items-center gap-4 text-sm text-gray-400">

        <!-- Logo -->
        <img src="/lp/logo.png" class="h-10">

        <p class="text-white">© 2026 CCSO, Inc.</p>
        <img src="/lp/garis.png" class="h-5">

        <p class="text-white">Contact Us</p>
        <img src="/lp/garis.png" class="h-5">

        <!-- Telepon -->
        <div class="flex items-center gap-2">
          <img src="/lp/telp.png" class="h-5">
          <p>+62 1234567890</p>
        </div>

        <!-- Media Sosial -->
        <div class="flex items-center gap-6 ml-2">
          <img src="/lp/tt.png" class="h-5 cursor-pointer">
          <img src="/lp/ig.png" class="h-5 cursor-pointer">
          <img src="/lp/wa.png" class="h-5 cursor-pointer">
          <img src="/lp/email.png" class="h-5 cursor-pointer">
        </div>

        <!-- Email Input -->
        <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
          <input type="email" placeholder="Sent to our Email..." class="bg-transparent px-3 py-1 text-sm w-54">
          <button class="bg-blue-500 px-3 py-1 text-white text-sm hover:bg-blue-600">›</button>
        </div>

      </div>
    </div>
  </footer>

</body>
</html>