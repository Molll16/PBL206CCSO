<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
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

  <div class="bg-surface px-6 flex items-center justify-between border-b border-borderSubtle mt-16 md:mt-0">
    <div class="flex gap-8">
      <a href="{{ route('profile-setting-admin') }}"
        class="py-3 text-textMuted text-sm hover:text-textMain transition font-medium">Profile Settings</a>
      <a href="{{ route('profile-agent') }}"
        class="py-3 text-brand text-sm border-b-2 border-brand font-medium">Agent</a>
    </div>
  </div>

  <div class="p-6 mx-auto">

    <div class="flex items-center gap-6 mb-8 p-6 bg-surface border border-borderSubtle rounded-xl">
      <div class="relative cursor-pointer group" onclick="document.getElementById('inputGambarProfil').click()">
        <img id="fotoProfil"
          class="w-20 h-20 rounded-full object-cover ring-2 ring-borderSubtle group-hover:ring-brand transition-all duration-300"
          src="/profilee/profile.png">
        <div
          class="absolute inset-0 rounded-full bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <i data-lucide="camera" class="w-5 h-5 text-white"></i>
        </div>
      </div>
      <input type="file" id="inputGambarProfil" accept="image/*" class="hidden" onchange="gantiGambarProfil(event)">
      <div>
        <h2 class="text-lg font-semibold text-textMain">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-textMuted">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>

    <main class="space-y-6">

      <div>
        <h3 class="text-sm font-semibold text-textMain mb-3 flex items-center gap-2">
          <i data-lucide="activity" class="w-4 h-4 text-brand"></i> Agent Log
        </h3>
        <div class="grid grid-cols-2 gap-4">
          <div
            class="bg-surface border border-borderSubtle p-5 rounded-xl group cursor-default hover:border-brand/30 transition-all">
            <p
              class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-textMain transition-colors">
              Total Agent</p>
            <h2 class="text-3xl font-bold mt-2 text-textMain">{{ $totalAgents }}</h2>
          </div>
          <div
            class="bg-surface border border-borderSubtle p-5 rounded-xl group cursor-default hover:border-green-500/30 transition-all">
            <p
              class="text-xs text-textMuted uppercase tracking-wider font-semibold group-hover:text-green-400 transition-colors">
              Assigned Agent</p>
            <h2 class="text-3xl font-bold mt-2 text-green-400 drop-shadow-[0_0_10px_rgba(34,197,94,0.4)]">
              {{ $assignedAgents }}</h2>
          </div>
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-sm font-semibold text-textMain flex items-center gap-2">
            <i data-lucide="server" class="w-4 h-4 text-brand"></i>
            Agents
            <span
              class="text-[10px] bg-page border border-borderSubtle px-2 py-0.5 rounded-full">{{ $agents->count() }}</span>
          </h3>
        </div>

        <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
              <thead class="bg-surface text-textMuted text-xs uppercase tracking-wider border-b border-borderSubtle">
                <tr>
                  <th class="p-4 font-semibold">Name</th>
                  <th class="p-4 font-semibold">IP Address</th>
                  <th class="p-4 font-semibold">Assigned To</th>
                  <th class="p-4 font-semibold">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-borderSubtle bg-page/30">
                @forelse($agents as $agent)
                  <tr class="hover:bg-surface/60 transition-colors">
                    <td class="p-4 font-semibold text-brand">{{ $agent['name'] }}</td>
                    <td class="p-4 text-textMuted font-mono text-xs">{{ $agent['ip'] }}</td>
                    <td class="p-4 text-textMain text-sm">
                      @php
                        $assigned = \App\Models\Agen::where('id_wazuh_agen', $agent['id'])->first();
                      @endphp
                      {{ $assigned->user->name ?? '-' }}
                    </td>
                    <td class="p-4">
                      @if($agent['status'] === 'active')
                        <span
                          class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">●
                          Active</span>
                      @elseif($agent['status'] === 'disconnected')
                        <span
                          class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">●
                          Disconnected</span>
                      @else
                        <span
                          class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">●
                          {{ ucfirst($agent['status']) }}</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center p-8 text-textMuted text-sm italic">No agents found</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div
            class="p-4 flex items-center justify-between text-xs text-textMuted border-t border-borderSubtle bg-surface/50">
            <div class="flex items-center gap-2">
              Rows per page:
              <select
                class="bg-page border border-borderSubtle text-textMuted rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-brand text-xs cursor-pointer">
                <option>5</option>
                <option>10</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <button class="hover:text-textMain transition px-1 flex items-center justify-center"><i
                  data-lucide="chevron-left" class="w-4 h-4"></i></button>
              <span class="text-brand font-bold bg-brand/10 border border-brand/20 px-2 py-0.5 rounded">1</span>
              <span class="hover:text-textMain cursor-pointer px-1">2</span>
              <button class="hover:text-textMain transition px-1 flex items-center justify-center"><i
                  data-lucide="chevron-right" class="w-4 h-4"></i></button>
            </div>
          </div>

        </div>
      </div>

    </main>
  </div>

  <script>
    lucide.createIcons();

    function gantiGambarProfil(event) {
      const file = event.target.files[0]
      if (!file) return
      const reader = new FileReader()
      reader.onload = function (e) {
        document.getElementById('fotoProfil').src = e.target.result
      }
      reader.readAsDataURL(file)
    }
  </script>

</body>

</html>