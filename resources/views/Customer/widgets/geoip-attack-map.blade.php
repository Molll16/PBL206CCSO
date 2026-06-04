{{-- GeoIP Attack Map Widget --}}

@php
    $geoAttacks = [
        ['country' => 'Russia', 'city' => 'Moscow', 'lat' => 55.7558, 'lng' => 37.6173, 'severity' => 'danger', 'attack' => 'Port Scan'],
        ['country' => 'China', 'city' => 'Beijing', 'lat' => 39.9042, 'lng' => 116.4074, 'severity' => 'warning', 'attack' => 'Brute Force'],
        ['country' => 'USA', 'city' => 'New York', 'lat' => 40.7128, 'lng' => -74.0060, 'severity' => 'info', 'attack' => 'API Abuse'],
        ['country' => 'Indonesia', 'city' => 'Batam', 'lat' => 1.0456, 'lng' => 104.0305, 'severity' => 'success', 'attack' => 'Login Attempt'],
    ];
@endphp

<div class="h-full w-full flex flex-col justify-between">

    {{-- Judul Widget --}}
    <div class="text-xs text-cyan-400 font-semibold mb-2 flex-shrink-0">
        🌍 GeoIP Attack Map
    </div>

    {{-- MAP CONTAINER --}}
    {{-- Menggunakan calc(100% - 24px) untuk menyisakan ruang bagi teks judul --}}
    <div id="attackMap" class="w-full rounded-xl border border-gray-700/60 bg-[#121318]"
        style="height: calc(100% - 24px); min-height: 120px;"></div>

</div>

{{-- Lempar data array ke scope global window agar dibaca script footer --}}
<script>
    window.geoAttacksData = @json($geoAttacks);
</script>