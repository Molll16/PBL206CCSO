<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/js/app.js')

    <style>
        body {
            background-color: #060810;
            background-image:
                radial-gradient(ellipse 80% 60% at 15% 20%, rgba(34, 211, 238, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 70%, rgba(99, 102, 241, 0.10) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 50% 95%, rgba(34, 211, 238, 0.06) 0%, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            box-shadow:
                0 4px 24px rgba(0, 0, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.065);
            border-color: rgba(255, 255, 255, 0.18);
            box-shadow:
                0 4px 32px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(34, 211, 238, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);
        }

        .glass-alert {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow:
                0 8px 40px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.10);
        }

        .glass-pill {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.09);
        }

        .glass-offline {
            background: rgba(239, 68, 68, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .glass-empty {
            background: rgba(255, 255, 255, 0.025);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .card-shine::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, transparent 50%);
            pointer-events: none;
        }
    </style>
</head>

<body class="text-gray-200 font-sans flex flex-col min-h-screen">

@include('Customer.components.header')

<main class="flex-1 p-6 relative">

    <!-- Welcome -->
    <div class="mb-6 flex items-center gap-3">
        <div class="w-[3px] h-6 rounded-full bg-cyan-400" style="box-shadow: 0 0 10px rgba(34,211,238,0.6)"></div>
        <h2 class="text-white text-lg tracking-wide">
            Welcome,
            <span class="text-cyan-400 hover:underline cursor-pointer">
                {{ auth()->user()->name }}
            </span>!
        </h2>
    </div>

    <!-- Alert Password -->
    @if(!auth()->user()->password_changed)
    <div id="pwd-alert"
         class="glass-alert absolute top-6 right-6 w-72 rounded-2xl p-5 z-20">

        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                 style="background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-amber-300 tracking-wide">Password Notice</p>
        </div>

        <p class="text-[12px] text-gray-400 mb-5 leading-relaxed">
            You must change your current password for a new one
        </p>

        <div class="flex justify-end gap-2">

            <button onclick="dismissAlert()"
                class="px-4 py-1.5 rounded-lg text-xs transition duration-300 text-gray-300"
                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.10);"
                onmouseover="this.style.background='rgba(255,255,255,0.09)'"
                onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                Later
            </button>

            <a href="{{ route('profile-setting') }}"
               class="px-4 py-1.5 rounded-lg text-xs text-white transition duration-300"
               style="background: rgba(6,182,212,0.85); box-shadow: 0 0 14px rgba(34,211,238,0.3);"
               onmouseover="this.style.background='rgba(6,182,212,1)'"
               onmouseout="this.style.background='rgba(6,182,212,0.85)'">
                Change Now
            </a>

        </div>
    </div>
    @endif

    @if($wazuhOffline)
        <div class="glass-offline mb-5 px-4 py-3 rounded-2xl text-red-300">
            <div class="flex items-center gap-3">

                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div>
                    <p class="font-semibold text-sm">Server Monitoring Offline</p>
                    <p class="text-xs mt-0.5" style="color: rgba(252,165,165,0.65)">
                        Data monitoring sementara tidak tersedia
                    </p>
                </div>

            </div>
        </div>
    @endif

    <!-- Active Dashboard Title -->
    <div class="mb-5 flex items-center gap-3">
        <div class="h-px flex-1" style="background: rgba(255,255,255,0.06)"></div>
        <div class="glass-pill flex items-center gap-2 px-4 py-1.5 rounded-full">
            <div class="w-1.5 h-1.5 rounded-full bg-cyan-400"
                 style="box-shadow: 0 0 6px rgba(34,211,238,0.9)"></div>
            <h3 class="text-gray-300 text-sm tracking-wide">
                {{ $dashboard->nama_dasbor ?? 'No Dashboard Active' }}
            </h3>
        </div>
        <div class="h-px flex-1" style="background: rgba(255,255,255,0.06)"></div>
    </div>

    <!-- Widget Area -->
    <div class="grid grid-cols-12 gap-4">

    @forelse($widgets as $item)

    <div class="col-span-{{ $item->kolom }}">

        <!-- Widget Label -->
        <p class="text-[11px] mb-2 ml-1 tracking-widest uppercase" style="color: rgba(156,163,175,0.7)">
            {{ $item->fitur->nama_fitur }}
        </p>

        <!-- Widget Card -->
        <div class="glass-card card-shine relative rounded-2xl h-48 p-4 transition duration-300">

            @if($item->fitur->nama_fitur === 'Agent Status')

                @include('Customer.widgets.agent-status')

                @elseif($item->fitur->nama_fitur === 'Network Traffic')
                @include('Customer.widgets.network-traffic')

                @elseif($item->fitur->nama_fitur === 'Service Status')
                @include('Customer.widgets.service-status')

                @elseif($item->fitur->nama_fitur === 'File Integrity Monitoring')
                @include('Customer.widgets.file-integrity')

                @elseif($item->fitur->nama_fitur === 'Security Alerts')
                @include('Customer.widgets.security-alerts')

                @elseif($item->fitur->nama_fitur === 'Threat Summary')
                @include('Customer.widgets.threat-summary')

                @elseif($item->fitur->nama_fitur === 'System Resources')
                @include('Customer.widgets.system_resources')

                @elseif($item->fitur->nama_fitur === 'Failed Login')
                @include('Customer.widgets.failed_login')

                @elseif($item->fitur->nama_fitur === 'Top Triggered Rules')
                @include('Customer.widgets.top_triggered_rules')

            @else

                <div class="h-full flex items-center justify-center">
                    <span class="text-sm text-center px-2" style="color: rgba(107,114,128,0.8)">
                        {{ $item->fitur->nama_fitur }}
                    </span>
                </div>

            @endif

        </div>

    </div>

    @empty

    <div class="col-span-12">
        <div class="glass-empty rounded-2xl h-56 flex flex-col items-center justify-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color: rgba(107,114,128,0.7)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
            </div>
            <span class="text-sm tracking-wide" style="color: rgba(107,114,128,0.7)">
                Belum ada dashboard aktif
            </span>
        </div>
    </div>

    @endforelse

    </div>

</main>

@include('Customer.components.footer')

<script>
function dismissAlert() {
    document.getElementById('pwd-alert').style.display = 'none';
}
</script>

</body>
</html>