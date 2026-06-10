<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

<body class="text-textMain font-sans flex flex-col min-h-screen bg-page antialiased">

    @include('Customer.components.header')

    <main class="flex-1 p-3 sm:p-4 md:p-6 relative">

        {{-- Welcome greeting --}}
        <div class="mb-4 sm:mb-6 flex items-center gap-3 animate-fade-in">
            <div class="w-[3px] h-6 rounded-full accent-bar flex-shrink-0"></div>
            <h2 class="text-textMain text-base sm:text-lg tracking-wide truncate">
                Welcome,
                <span class="text-brand hover:text-brandHover cursor-pointer transition-colors">
                    {{ auth()->user()->name }}
                </span>!
            </h2>
        </div>

        {{-- Password change alert - responsive positioning --}}
        @if(!auth()->user()->password_changed)
            <div id="pwd-alert"
                class="alert-card
                    w-full sm:w-72
                    rounded-xl p-4 sm:p-5 z-20 animate-fade-in mb-4
                    sm:absolute sm:top-6 sm:right-6 sm:mb-0">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-amber-400 tracking-wide">Password Notice</p>
                </div>
                <p class="text-[12px] text-textMuted mb-4 sm:mb-5 leading-relaxed">
                    You must change your current password for a new one
                </p>
                <div class="flex justify-end gap-2">
                    <button onclick="dismissAlert()"
                        class="px-4 py-1.5 rounded-lg text-xs transition duration-200 text-textMuted hover:text-textMain"
                        style="background: rgba(255,255,255,0.04); border: 1px solid #262833;">
                        Later
                    </button>
                    <a href="{{ route('profile-setting') }}"
                        class="px-4 py-1.5 rounded-lg text-xs text-white transition duration-200"
                        style="background: #6366f1; box-shadow: 0 0 14px rgba(99,102,241,0.35);"
                        onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                        Change Now
                    </a>
                </div>
            </div>
        @endif

        {{-- Wazuh offline banner --}}
        @if($wazuhOffline)
            <div class="offline-banner mb-4 sm:mb-5 px-3 sm:px-4 py-3 rounded-lg text-red-400 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Server Monitoring Offline</p>
                        <p class="text-xs mt-0.5 text-red-300/70">Data monitoring sementara tidak tersedia</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Dashboard name divider --}}
        <div class="mb-4 sm:mb-5 flex items-center gap-3 animate-fade-in">
            <div class="h-px flex-1" style="background: #262833"></div>
            <div class="pill-divider flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full flex-shrink-0">
                <div class="w-1.5 h-1.5 rounded-full pulse-dot animate-pulse"></div>
                <h3 class="text-textMuted text-xs sm:text-sm tracking-wide truncate max-w-[160px] sm:max-w-none">
                    {{ $dashboard->nama_dasbor ?? 'No Dashboard Active' }}
                </h3>
            </div>
            <div class="h-px flex-1" style="background: #262833"></div>
        </div>

        {{-- Widget grid --}}
        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-12 gap-3 sm:gap-4">

            @forelse($widgets as $item)
                @php
                    $wKolom = $item->kolom ?? 6;
                    $wBaris = $item->baris ?? 2;
                    $wHeight = $wBaris * 85;

                    // Responsive column spans:
                    // xs (4-col grid): always full width (col-span-4)
                    // sm (6-col grid): half or full depending on original kolom
                    // md (8-col grid): scale proportionally
                    // lg (12-col grid): original value
                    $smSpan = $wKolom <= 4 ? 3 : 6;   // half or full on sm
                    $mdSpan = min(8, max(4, (int)round($wKolom * 8 / 12))); // scale to 8-col
                @endphp

                <div class="col-span-4 sm:col-span-{{ $smSpan }} md:col-span-{{ $mdSpan }} lg:col-span-{{ $wKolom }} hover-scale animate-fade-in">

                    <p class="text-[10px] mb-2 ml-1 tracking-widest uppercase font-semibold" style="color: #787f99">
                        {{ $item->fitur->nama_fitur }}
                    </p>

                    <div class="widget-card rounded-xl p-3 sm:p-4 no-scrollbar flex flex-col {{ $item->fitur->nama_fitur === 'GeoIP Attack Map' ? '' : 'overflow-y-auto' }}"
                        style="height: {{ $wHeight }}px; min-height: {{ $wHeight }}px;">

                        @if($item->fitur->nama_fitur === 'Agent Status')
                            @include('Customer.widgets.agent-status')

                        @elseif($item->fitur->nama_fitur === 'Network Traffic')
                            @include('Customer.widgets.network-traffic')

                        @elseif($item->fitur->nama_fitur === 'Service Status')
                            @include('Customer.widgets.service-status')

                        @elseif($item->fitur->nama_fitur === 'File Integrity Monitoring')
                            @include('Customer.widgets.file-integrity')

                        @elseif($item->fitur->nama_fitur === 'Active Connections')
                            @include('Customer.widgets.active_connection')

                        @elseif($item->fitur->nama_fitur === 'Security Alerts')
                            @include('Customer.widgets.security-alerts')

                        @elseif($item->fitur->nama_fitur === 'Threat Summary')
                            @include('Customer.widgets.threat-summary')

                        @elseif($item->fitur->nama_fitur === 'System Resources')
                            @include('Customer.widgets.system_resources')

                        @elseif($item->fitur->nama_fitur === 'Failed Login Monitoring')
                            @include('Customer.widgets.failed_login')

                        @elseif($item->fitur->nama_fitur === 'Top Triggered Rules')
                            @include('Customer.widgets.top_triggered_rules')

                        @elseif($item->fitur->nama_fitur === 'Firewall Events')
                            @include('Customer.widgets.firewall-event')

                        @elseif($item->fitur->nama_fitur === 'User Login Activity')
                            @include('Customer.widgets.user-login-activity')

                        @elseif($item->fitur->nama_fitur === 'GeoIP Attack Map')
                            @include('Customer.widgets.geoip-attack-map')

                        @else
                            <div class="h-full flex items-center justify-center">
                                <span class="text-sm text-center px-2 text-textMuted">
                                    {{ $item->fitur->nama_fitur }}
                                </span>
                            </div>
                        @endif

                    </div>

                </div>

            @empty

                <div class="col-span-4 sm:col-span-6 md:col-span-8 lg:col-span-12 animate-fade-in">
                    <div class="empty-card rounded-xl h-48 sm:h-56 flex flex-col items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                            style="background: rgba(255,255,255,0.03); border: 1px solid #262833">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-textMuted" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                            </svg>
                        </div>
                        <span class="text-sm tracking-wide text-textMuted italic">
                            Belum ada dashboard aktif
                        </span>
                    </div>
                </div>

            @endforelse

        </div>

    </main>

    @include('Customer.components.footer')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        lucide.createIcons();

        function dismissAlert() {
            document.getElementById('pwd-alert').style.display = 'none';
        }

        document.addEventListener("DOMContentLoaded", function () {
            const mapContainer = document.getElementById('attackMap');

            if (mapContainer) {
                // Inisialisasi peta dunia
                const map = L.map('attackMap', {
                    worldCopyJump: true,
                    // PERBAIKAN 2: Batasi zoom minimum agar tampilan tidak berulang aneh saat boksnya kecil
                    minZoom: 1,
                }).setView([15, 10], 1);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                function getSeverityColor(severity) {
                    switch (severity) {
                        case 'danger': return '#ef4444';
                        case 'warning': return '#f59e0b';
                        case 'info': return '#3b82f6';
                        default: return '#22c55e';
                    }
                }

                const attackPoints = window.geoAttacksData || [];

                attackPoints.forEach((item) => {
                    const markerColor = getSeverityColor(item.severity);

                    L.circleMarker([item.lat, item.lng], {
                        radius: 6,
                        color: markerColor,
                        fillColor: markerColor,
                        fillOpacity: 0.8,
                        weight: 1.5
                    })
                        .addTo(map)
                        .bindPopup(`
                            <div style="color: #121318; font-family: sans-serif; font-size: 11px;">
                                <b>${item.country} - ${item.city}</b><br>
                                <span style="color: #6366f1; font-weight: bold;">${item.attack}</span><br>
                                <small>Severity: ${item.severity}</small>
                            </div>
                        `);
                });

                // PERBAIKAN 3: Timeout diperpanjang sedikit (450ms) untuk memastikan 
                // engine browser selesai merender CSS Grid layout secara sempurna sebelum peta di-resize.
                setTimeout(() => {
                    map.invalidateSize();
                }, 450);
            }
        });
    </script>

</body>

</html>