<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customization Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>

  @vite('resources/css/customer/profile/profilecustom.css')
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
      <a href="{{ route('profile-setting') }}"
        class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Profile Settings</a>
      <a href="{{ route('profile-server') }}"
        class="text-sm cursor-pointer text-textMuted hover:text-brand transition-colors">Server</a>
      <a href="{{ route('profile-custom') }}" class="text-sm cursor-pointer text-brand">Customization Dashboard</a>
    </div>
  </div>

  <div class="flex p-6 gap-6 flex-1">

    <div class="mt-32">
      <div class="profile-card p-6 mx-8 mt-6 rounded-xl">
        <h2 class="mt-2 font-semibold text-textMain">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-textMuted">{{ auth()->user()->no_telp }}</p>
      </div>
    </div>

    <div class="w-2/3 space-y-6 pl-10">

      <div class="animate-fade-in">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Customization</h3>
        </div>

        <div class="stat-card rounded-xl p-6 grid grid-cols-3 text-center gap-4">
          <div>
            <p class="text-sm text-textMuted">Total Dashboard</p>
            <p class="text-2xl font-bold mt-1 text-textMain">{{ sprintf('%02d', $totalDashboard ?? 0) }}</p>
          </div>

          <div>
            <p class="text-sm text-textMuted">Active Dashboard</p>
            <p class="text-2xl font-bold mt-1 text-textMain">{{ sprintf('%02d', $activeDashboard ?? 0) }}</p>
          </div>

          <div>
            <p class="text-sm text-textMuted">Dashboard In Use</p>
            <p class="text-2xl font-bold mt-1 text-brand">ID: {{ $dashboardInUse }}</p>
          </div>
        </div>
      </div>

      <div class="animate-fade-in">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-[3px] h-5 rounded-full accent-bar"></div>
          <h3 class="font-semibold text-textMain tracking-wide">Your Dashboard</h3>
        </div>

        <div class="table-wrapper rounded-xl overflow-hidden">

          <div class="grid text-sm font-semibold table-header" style="grid-template-columns: 60px 1fr 180px 120px;">
            <p class="px-4 py-3 table-cell-border text-center text-textMuted tracking-wider uppercase text-xs">ID</p>
            <p class="px-4 py-3 table-cell-border text-center text-textMuted tracking-wider uppercase text-xs">Dashboard Name</p>
            <p class="px-4 py-3 text-center text-textMuted tracking-wider uppercase text-xs">Status</p>
          </div>

          <div id="tabelDashboard">
            @forelse($dashboards as $item)
              <div class="grid items-center text-sm table-row hover:bg-[#1e1f29] transition-colors"
                style="grid-template-columns: 60px 1fr 180px 120px;">

                <p class="px-4 py-3 table-cell-border text-center style-muted-text">{{ sprintf('%02d', $item->id) }}</p>

                <p class="px-4 py-3 table-cell-border text-left text-textMain">{{ $item->nama_dasbor }}</p>

                <p class="px-4 py-3 text-center font-semibold 
                      @if($item->status_dasbor === 'aktif') text-green-400 
                      
                      @else text-textMuted @endif">
                  {{ $item->status_dasbor === 'aktif' ? 'In Use' : 'Active' }}
                </p>

              </div>
            @empty
              <div class="p-8 text-center text-sm text-textMuted italic bg-surface">
                Belum ada kustomisasi dashboard yang tersedia.
              </div>
            @endforelse
          </div>

        </div>
      </div>

    </div>
  </div>

@include('Customer.components.footer')

</body>

</html>