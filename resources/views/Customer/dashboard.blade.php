<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Central Cyber Security Office</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/js/app.js')
</head>

<body class="bg-[#2B2D34] text-gray-200 font-sans flex flex-col min-h-screen">

@include('components.header')

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

            <a href="/changepw"
               class="px-4 py-1.5 rounded-md text-xs bg-[#00A8E8] text-white hover:bg-blue-500 transition">
                Change Now
            </a>

        </div>
    </div>


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
    <div class="border border-white rounded-lg h-48 p-4 hover:bg-white/5 transition">

        @if($item->fitur->nama_fitur === 'Agent Status')

            @include('Customer.widgets.agent-status')

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

@include('components.footer')

<script>
function dismissAlert() {
    document.getElementById('pwd-alert').style.display = 'none';
}
</script>

</body>
</html>