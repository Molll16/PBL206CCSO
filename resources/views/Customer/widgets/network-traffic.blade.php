{{-- resources/views/Customer/widgets/network-traffic.blade.php --}}

@php
    $networkStats = ['inbound' => '2.4', 'outbound' => '1.1'];
    $networkInterfaces = [
        ['name' => 'eth0',  'ip' => '192.168.1.1', 'direction' => 'up',   'speed' => '1.2G'],
        ['name' => 'eth1',  'ip' => '10.0.0.5',    'direction' => 'down', 'speed' => '850M'],
        ['name' => 'wlan0', 'ip' => '172.16.0.3',  'direction' => 'up',   'speed' => '340M'],
    ];
@endphp

<div class="h-full flex flex-col ">

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-2 mb-2 flex-shrink-0">

        <div class="bg-white/5 rounded-lg px-3 py-2">
            <p class="text-[10px] text-gray-400 mb-0.5">Inbound</p>
            <p class="text-sm font-semibold text-white">
                {{ $networkStats['inbound'] }}
                <span class="text-[10px] text-gray-400 font-normal">Gbps</span>
            </p>
        </div>

        <div class="bg-white/5 rounded-lg px-3 py-2">
            <p class="text-[10px] text-gray-400 mb-0.5">Outbound</p>
            <p class="text-sm font-semibold text-white">
                {{ $networkStats['outbound'] }}
                <span class="text-[10px] text-gray-400 font-normal">Gbps</span>
            </p>
        </div>

    </div>

    {{-- List Interface --}}
    <div class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        @foreach ($networkInterfaces as $iface)

            <div class="flex justify-between items-center bg-white/5 px-3 py-2 rounded-lg">

                {{-- LEFT --}}
                <div class="min-w-0 mr-3">
                    <p class="text-xs font-medium truncate text-white">
                        {{ $iface['name'] }}
                    </p>
                    <p class="text-[11px] text-gray-400 truncate">
                        {{ $iface['ip'] }}
                    </p>
                </div>

                {{-- RIGHT --}}
                <div class="shrink-0">
                    @if ($iface['direction'] === 'up')
                        <span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">
                            ↑ {{ $iface['speed'] }}
                        </span>
                    @else
                        <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded text-[10px]">
                            ↓ {{ $iface['speed'] }}
                        </span>
                    @endif
                </div>

            </div>

        @endforeach

    </div>

</div>