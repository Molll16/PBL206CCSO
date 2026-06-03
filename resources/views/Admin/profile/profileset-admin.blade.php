<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  @vite('resources/css/app.css')
  @vite('resources/js/app.js')

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
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-page text-textMain font-sans antialiased">

@include('Admin.components.header-admin')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="bg-surface px-6 flex items-center justify-between border-b border-borderSubtle mt-16 md:mt-0">
    <div class="flex gap-8">
      <a href="{{ route('profile-setting-admin') }}" class="py-3 text-brand text-sm border-b-2 border-brand font-medium">Profile Settings</a>
      <a href="{{ route('profile-agent') }}" class="py-3 text-textMuted text-sm hover:text-textMain transition font-medium">Agent</a>
    </div>
  </div>

  <div class="p-6 mx-auto">

    <!-- ===== PROFIL HEADER ===== -->
    <div class="flex items-center gap-6 mb-8 p-6 bg-surface border border-borderSubtle rounded-xl">
      <div class="relative cursor-pointer group" onclick="document.getElementById('inputGambarProfil').click()">
        <img id="fotoProfil" class="w-20 h-20 rounded-full object-cover ring-2 ring-borderSubtle group-hover:ring-brand transition-all duration-300" src="/profilee/profile.png">
        <div class="absolute inset-0 rounded-full bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <i data-lucide="camera" class="w-5 h-5 text-white"></i>
        </div>
      </div>
      <input type="file" id="inputGambarProfil" accept="image/*" class="hidden" onchange="gantiGambarProfil(event)">
      <div>
        <h2 class="text-lg font-semibold text-textMain">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-textMuted">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>

    <main class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- ===== FORM UPDATE PROFIL ===== -->
      <div class="bg-surface border border-borderSubtle rounded-xl p-6 space-y-5">
        <h3 class="text-sm font-semibold text-textMain flex items-center gap-2 border-b border-borderSubtle pb-4">
          <i data-lucide="user-cog" class="w-4 h-4 text-brand"></i> Personal Information
        </h3>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
          @csrf

          @if(session('success') && !session('password_success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
              <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
              {{ session('success') }}
            </div>
          @endif

          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-textMuted uppercase tracking-wider">Full Name</label>
            <input type="text" name="name" value="{{ auth()->user()->name }}"
              class="bg-page border border-borderSubtle rounded-lg px-4 py-2.5 text-sm text-textMain focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all">
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-textMuted uppercase tracking-wider">Email</label>
            <input type="email" name="email" value="{{ auth()->user()->email }}"
              class="bg-page border border-borderSubtle rounded-lg px-4 py-2.5 text-sm text-textMain focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all">
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-textMuted uppercase tracking-wider">Personal Phone Number</label>
            <input type="text" name="no_telp" value="{{ auth()->user()->no_telp }}"
              class="bg-page border border-borderSubtle rounded-lg px-4 py-2.5 text-sm text-textMain focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all">
          </div>

          <button type="submit" class="w-full bg-brand hover:bg-brandHover transition text-white px-5 py-2.5 rounded-lg font-semibold text-sm shadow-lg shadow-brand/20 flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Save Changes
          </button>
        </form>
      </div>

      <!-- ===== FORM GANTI PASSWORD ===== -->
      <div class="bg-surface border border-borderSubtle rounded-xl p-6 space-y-5">
        <h3 class="text-sm font-semibold text-textMain flex items-center gap-2 border-b border-borderSubtle pb-4">
          <i data-lucide="lock" class="w-4 h-4 text-brand"></i> Change Password
        </h3>

        <form action="{{ route('changepw.update') }}" method="POST" class="space-y-4">
          @csrf

          @if(session('success') && session('password_success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
              <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
              {{ session('success') }}
            </div>
          @endif

          @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
              <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
              {{ session('error') }}
            </div>
          @endif

          @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
              <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
              @if($errors->first() == 'The new password field confirmation does not match.')
                The password confirmation does not match.
              @elseif($errors->first() == 'The new password must be at least 6 characters.')
                The new password must be at least 6 characters.
              @else
                {{ $errors->first() }}
              @endif
            </div>
          @endif

          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-textMuted uppercase tracking-wider">Current Password</label>
            <input type="password" name="current_password" placeholder="Enter current password" required
              class="bg-page border border-borderSubtle rounded-lg px-4 py-2.5 text-sm text-textMain placeholder-textMuted/50 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all">
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-textMuted uppercase tracking-wider">New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password" required
              class="bg-page border border-borderSubtle rounded-lg px-4 py-2.5 text-sm text-textMain placeholder-textMuted/50 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all">
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-textMuted uppercase tracking-wider">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" placeholder="Confirm new password" required
              class="bg-page border border-borderSubtle rounded-lg px-4 py-2.5 text-sm text-textMain placeholder-textMuted/50 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand/30 transition-all">
          </div>

          <button type="submit" class="w-full bg-brand hover:bg-brandHover transition text-white px-5 py-2.5 rounded-lg font-semibold text-sm shadow-lg shadow-brand/20 flex items-center justify-center gap-2">
            <i data-lucide="key" class="w-4 h-4"></i> Update Password
          </button>
        </form>
      </div>

    </main>
  </div>

  <!-- ===== JAVASCRIPT ===== -->
  <script>
    lucide.createIcons();

    // Fungsi untuk mengganti foto profil saat pengguna memilih gambar dari perangkat
    function gantiGambarProfil(event) {
      const file = event.target.files[0]
      if (!file) return
      const reader = new FileReader()
      reader.onload = function(e) {
        document.getElementById('fotoProfil').src = e.target.result
      }
      reader.readAsDataURL(file)
    }
  </script>

</body>
</html>