<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Server</title>
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
<body class="min-h-screen bg-page text-textMain font-sans antialiased flex flex-col">

@include('Customer.components.header')

  <!-- ===== TAB NAVIGASI PROFIL ===== -->
  <div class="bg-surface px-6 flex items-center justify-between border-b border-borderSubtle mt-16 md:mt-0">
    <div class="flex gap-8">
      <a href="{{ route('profile-setting') }}" class="py-3 text-textMuted text-sm hover:text-textMain transition font-medium">Profile Settings</a>
      <a href="{{ route('profile-server') }}" class="py-3 text-brand text-sm border-b-2 border-brand font-medium">Server</a>
      <a href="{{ route('profile-custom') }}" class="py-3 text-textMuted text-sm hover:text-textMain transition font-medium">Customization Dashboard</a>
    </div>
  </div>

  <div class="p-6 mx-auto w-full flex-1">

    <!-- ===== PROFIL HEADER ===== -->
    <div class="flex items-center gap-6 mb-8 p-6 bg-surface border border-borderSubtle rounded-xl">
      <div class="relative cursor-pointer group">
        <img id="fotoProfil" class="w-20 h-20 rounded-full object-cover ring-2 ring-borderSubtle" src="/profilee/profile.png">
      </div>
      <div>
        <h2 class="text-lg font-semibold text-textMain">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-textMuted">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>

    <main class="space-y-6">

      <!-- ===== AGENT LOG STATS ===== -->
      <div>
        <h3 class="text-sm font-semibold text-textMain mb-3 flex items-center gap-2">
          <i data-lucide="activity" class="w-4 h-4 text-brand"></i> Agent Log
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-surface border border-borderSubtle p-5 rounded-xl group cursor-default hover:border-brand/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-textMain transition-colors">Total Agents</p>
            <h2 class="text-3xl font-bold mt-2 text-textMain">{{ sprintf('%02d', $agentsTotal ?? 0) }}</h2>
          </div>
          <div class="bg-surface border border-borderSubtle p-5 rounded-xl group cursor-default hover:border-green-500/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-green-400 transition-colors">Active Agents</p>
            <h2 class="text-3xl font-bold mt-2 text-green-400">{{ sprintf('%02d', $activeAgents ?? 0) }}</h2>
          </div>
          <div class="bg-surface border border-borderSubtle p-5 rounded-xl group cursor-default hover:border-red-500/30 transition-all">
            <p class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-red-400 transition-colors">Disconnect Agents</p>
            <h2 class="text-3xl font-bold mt-2 text-red-400">{{ sprintf('%02d', $disconnectAgents ?? 0) }}</h2>
          </div>
        </div>
      </div>

      <!-- ===== AGENTS TABLE ===== -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-sm font-semibold text-textMain flex items-center gap-2">
            <i data-lucide="server" class="w-4 h-4 text-brand"></i>
            Agents
            <span class="text-[10px] bg-page border border-borderSubtle px-2 py-0.5 rounded-full text-textMuted">{{ count($agents) }}</span>
          </h3>
        </div>

        <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
              <thead class="bg-surface text-textMuted text-xs uppercase tracking-wider border-b border-borderSubtle">
                <tr>
                  <th class="p-4 font-semibold text-left">Server Name</th>
                  <th class="p-4 font-semibold">IP Address</th>
                  <th class="p-4 font-semibold text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-borderSubtle bg-page/30">
                @forelse($agents as $agent)
                <tr class="hover:bg-surface/60 transition-colors">
                  <td class="p-4 text-left font-semibold text-brand">{{ $agent->server_name }}</td>
                  <td class="p-4 text-textMuted font-mono text-xs">{{ $agent->server_ip }}</td>
                  <td class="p-4 text-right">
                    @if($agent->status === 'Active' || $agent->status === 'active')
                      <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">● Active</span>
                    @elseif($agent->status === 'Pending' || $agent->status === 'pending')
                      <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">● Pending</span>
                    @else
                      <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">● {{ $agent->status }}</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="3" class="text-center p-8 text-textMuted text-sm italic">Tidak ada server atau agent yang terdaftar pada akun Anda.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </main>
  </div>

@include('Customer.components.footer')

  <script>
    lucide.createIcons();
  </script>

</body>
</html>