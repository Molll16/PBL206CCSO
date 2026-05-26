<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/js/app.js')
</head>

<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('Customer.components.header')

<main class="flex-1 p-4 relative">

    <!-- Welcome -->
    <div class="mb-5 inline-block border-b-2 border-white pr-4 pb-1">
        <h2 class="text-white text-lg">
            Welcome,
            <span class="text-[#4DA8DA] hover:underline cursor-pointer">
                {{ auth()->user()->name }}
            </span>!
        </h2>
    </div>

    <!-- Alert Password -->
    @if(!auth()->user()->password_changed)
    <div id="pwd-alert"
         class="absolute top-4 right-4 w-72 bg-[#212121] border border-gray-500 rounded-xl p-5 shadow-2xl z-20">

        <p class="text-[13px] text-gray-300 mb-6 leading-relaxed">
            You must change your current password for a new one
        </p>

        <div class="flex justify-end gap-2">

            <button onclick="dismissAlert()"
                class="px-4 py-1.5 rounded-md text-xs bg-[#3A3D42] text-gray-300 hover:bg-gray-600 transition">
                Later
            </button>

            <a href="{{ route('changepw') }}"
               class="px-4 py-1.5 rounded-md text-xs bg-[#00A8E8] text-white hover:bg-blue-500 transition">
                Change Now
            </a>

        </div>
    </div>
    @endif

    @if($wazuhOffline)

        <div class="mb-4 bg-red-500/10 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856
                             c1.54 0 2.502-1.667 1.732-3L13.732 4
                             c-.77-1.333-2.694-1.333-3.464 0L3.34 16
                             c-.77 1.333.192 3 1.732 3z" />

                </svg>

                <div>
                    <p class="font-semibold">
                        Server Monitoring Offline
                    </p>

                    <p class="text-sm text-red-200">
                        Data monitoring sementara tidak tersedia
                    </p>
                </div>

            </div>
        </div>

    @endif


    <!-- Judul Dashboard Aktif -->
    <div class="mb-4">
        <h3 class="text-white text-xl font-semibold">
            {{ $dashboard->nama_dasbor ?? 'No Dashboard Active' }}
        </h3>
    </div>


<!-- Widget Area -->
<div class="grid grid-cols-12 gap-3">

@forelse($widgets as $item)

<div class="col-span-{{ $item->kolom }}">

    <!-- Judul -->
    <p class="text-sm mb-2 ml-1">
        {{ $item->fitur->nama_fitur }}
    </p>

    <!-- Card -->
    <div class="border border-white rounded-lg h-48 p-4 hover:bg-white/5 transition ">

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

        @else
        
            <div class="h-full flex items-center justify-center">
                <span class="text-gray-400 font-medium text-center px-2">
                    {{ $item->fitur->nama_fitur }}
                </span>
            </div>

        @endif

    </div>

</div>

@empty

<div class="col-span-12">
    <div class="border border-white rounded-lg h-56 flex items-center justify-center">
        <span class="text-gray-500 text-lg">
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