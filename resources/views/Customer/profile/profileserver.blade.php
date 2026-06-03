<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Server</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/css/customer/profile/profileserver.css')
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

<body class="text-textMain font-sans flex flex-col min-h-screen bg-page antialiased">

  @include('Customer.components.header')

  <div class="flex items-center py-3 tab-border">
    <img class="w-48 h-48 mt-40 ml-14 absolute" src="/profilee/profile.png">

    <div class="flex items-center gap-10 ml-[330px]">
      <a href="{{ route('profile-setting') }}" class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Profile Settings</a>
      <a href="{{ route('profile-server') }}" class="text-sm cursor-pointer text-brand">Server</a>
      <a href="{{ route('profile-custom') }}" class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Customization Dashboard</a>
    </div>
  </div>

  <div class="flex p-6 gap-6 flex-1">

    <div class="mt-32">
      <div class="profile-card p-6 mx-8 mt-6 rounded-xl">
        <h2 class="mt-2 font-semibold text-textMain">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-textMuted">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>

    <div class="w-3/4 space-y-6 pl-10">

      <div class="animate-fade-in">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Server</h3>
        </div>

        <div class="stat-card rounded-xl p-6 grid grid-cols-3 text-center gap-4">
          <div>
            <p class="text-sm text-textMuted">Agents Total</p>
            <p class="text-2xl font-bold mt-1 text-textMain">{{ sprintf('%02d', $agentsTotal ?? 0) }}</p>
          </div>

          <div>
            <p class="text-sm text-textMuted">Active Agents</p>
            <p class="text-2xl font-bold mt-1 text-textMain">{{ sprintf('%02d', $activeAgents ?? 0) }}</p>
          </div>

          <div>
            <p class="text-sm text-textMuted">Disconnect Agents</p>
            <p class="text-2xl font-bold mt-1 text-textMain">{{ sprintf('%02d', $disconnectAgents ?? 0) }}</p>
          </div>
        </div>
      </div>

      <div class="animate-fade-in">

        <div class="grid grid-cols-3 gap-4 mb-2 text-xs font-semibold px-1 text-textMuted tracking-wider uppercase">
          <p>Server Name</p>
          <p>Server IP</p>
          <p>Agent Status</p>
        </div>

        @forelse($agents as $agent)
          <div class="grid grid-cols-3 gap-4 items-center mb-3">
            <input type="text" value="{{ $agent->server_name }}" readonly class="server-input">
            <input type="text" value="{{ $agent->server_ip }}" readonly class="server-input">

            <div>
              <button class="border rounded-lg py-2 text-sm w-24 font-medium capitalize
                  @if($agent->status === 'Active' || $agent->status === 'active') border-blue-400 text-blue-400
                  @elseif($agent->status === 'Pending' || $agent->status === 'pending') border-yellow-400 text-yellow-400
                  @else border-red-500 text-red-500 @endif">
                {{ $agent->status }}
              </button>
            </div>
          </div>
        @empty
          <div class="p-8 text-center text-sm text-textMuted italic bg-surface rounded-xl">
            Tidak ada server atau agent yang terdaftar pada akun Anda.
          </div>
        @endforelse

      </div>

    </div>

  </div>

  <footer class="footer-bar px-10 py-4">
    <div class="mt-2 px-1 flex flex-wrap items-center gap-4 text-sm text-textMuted">
      <img src="/lp/logo.png" class="h-10">
      <p class="text-textMain">© 2026 CCSO, Inc.</p>
    </div>
  </footer>

</body>

</html>