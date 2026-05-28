{{-- GeoIP Attack Map Widget --}}

@php
$geoAttacks = [
    ['country'=>'Russia','city'=>'Moscow','lat'=>55.7558,'lng'=>37.6173,'severity'=>'danger','attack'=>'Port Scan'],
    ['country'=>'China','city'=>'Beijing','lat'=>39.9042,'lng'=>116.4074,'severity'=>'warning','attack'=>'Brute Force'],
    ['country'=>'USA','city'=>'New York','lat'=>40.7128,'lng'=>-74.0060,'severity'=>'info','attack'=>'API Abuse'],
    ['country'=>'Indonesia','city'=>'Batam','lat'=>1.0456,'lng'=>104.0305,'severity'=>'success','attack'=>'Login Attempt'],
];
@endphp

{{-- Container Flex Dashboard --}}
<div class="h-full flex flex-col">

    {{-- Title (optional) --}}
    <div class="text-xs text-cyan-400 font-semibold mb-2">
        🌍 GeoIP Attack Map
    </div>

    {{-- MAP CONTAINER --}}
    <div
        id="attackMap"
        class="flex-1 w-full rounded-xl border border-gray-700 overflow-hidden
               [&::-webkit-scrollbar]:hidden [scrollbar-width:none]"
    ></div>

</div>

{{-- Data --}}
<script>
    const geoAttacks = @json($geoAttacks);
</script>

{{-- Leaflet --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Init map (important: delay resize fix for flex container)
    const map = L.map('attackMap', {
        worldCopyJump: true
    }).setView([20, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    function getColor(severity) {
        switch (severity) {
            case 'danger': return '#ef4444';
            case 'warning': return '#f59e0b';
            case 'info': return '#3b82f6';
            default: return '#22c55e';
        }
    }

    geoAttacks.forEach((item) => {
        const color = getColor(item.severity);

        L.circleMarker([item.lat, item.lng], {
            radius: 7,
            color: color,
            fillColor: color,
            fillOpacity: 0.8,
            weight: 2
        })
        .addTo(map)
        .bindPopup(`
            <b>${item.country} - ${item.city}</b><br>
            ${item.attack}<br>
            <small>${item.severity}</small>
        `);
    });

    // 🔥 FIX: Leaflet resize issue in flex layouts
    setTimeout(() => {
        map.invalidateSize();
    }, 200);
</script>