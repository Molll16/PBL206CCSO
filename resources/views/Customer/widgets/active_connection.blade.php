{{-- Active Connection Widget --}}

@php
$activeConnections = [
    [
        'src_ip' => '185.143.223.10',
        'dst_ip' => '192.168.1.10',
        'src_port' => 52344,
        'dst_port' => 22,
        'protocol' => 'TCP',
        'service' => 'SSH',
        'status' => 'ESTABLISHED',
        'duration' => '2m 10s',
        'bytes_in' => 2048,
        'bytes_out' => 890,
        'country' => 'Russia',
        'risk' => 'high'
    ],
    [
        'src_ip' => '101.43.12.77',
        'dst_ip' => '192.168.1.10',
        'src_port' => 49821,
        'dst_port' => 443,
        'protocol' => 'TCP',
        'service' => 'HTTPS',
        'status' => 'SYN_SENT',
        'duration' => '30s',
        'bytes_in' => 120,
        'bytes_out' => 60,
        'country' => 'China',
        'risk' => 'medium'
    ],
    [
        'src_ip' => '45.88.99.21',
        'dst_ip' => '192.168.1.10',
        'src_port' => 61012,
        'dst_port' => 80,
        'protocol' => 'TCP',
        'service' => 'HTTP',
        'status' => 'ESTABLISHED',
        'duration' => '5m 44s',
        'bytes_in' => 10450,
        'bytes_out' => 2200,
        'country' => 'USA',
        'risk' => 'low'
    ],
    [
        'src_ip' => '36.85.12.9',
        'dst_ip' => '192.168.1.10',
        'src_port' => 53321,
        'dst_port' => 3306,
        'protocol' => 'TCP',
        'service' => 'MySQL',
        'status' => 'CLOSE_WAIT',
        'duration' => '10m 12s',
        'bytes_in' => 560,
        'bytes_out' => 320,
        'country' => 'Indonesia',
        'risk' => 'low'
    ],
];
@endphp

<div class="h-full flex flex-col">

    {{-- HEADER --}}
    <div class="text-xs text-cyan-400 font-semibold mb-2">
        🔌 Active Connections
    </div>

    {{-- LIST --}}
    <div class="flex-1 space-y-2 overflow-y-auto [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        @foreach($activeConnections as $conn)

            @php
                $riskColor = match($conn['risk']) {
                    'high' => 'red',
                    'medium' => 'yellow',
                    default => 'green'
                };

                $statusColor = match($conn['status']) {
                    'ESTABLISHED' => 'text-green-400',
                    'SYN_SENT' => 'text-yellow-400',
                    'CLOSE_WAIT' => 'text-red-400',
                    default => 'text-cyan-400'
                };
            @endphp

            <div class="
                rounded-lg border px-3 py-2

                @if($conn['risk'] == 'high')
                    border-red-500/30 bg-red-500/10
                @elseif($conn['risk'] == 'medium')
                    border-yellow-500/30 bg-yellow-500/10
                @else
                    border-green-500/30 bg-green-500/10
                @endif
            ">

                <div class="flex justify-between items-start">

                    {{-- LEFT --}}
                    <div class="min-w-0 mr-3">

                        <p class="text-xs font-medium text-white truncate">
                            {{ $conn['src_ip'] }} → {{ $conn['dst_ip'] }}
                        </p>

                        <p class="text-[11px] text-gray-400 truncate">
                            {{ $conn['service'] }} ({{ $conn['protocol'] }}) • Port {{ $conn['src_port'] }} → {{ $conn['dst_port'] }}
                        </p>

                        <p class="text-[10px] text-gray-500 truncate">
                            {{ $conn['country'] }} • {{ $conn['duration'] }} •
                            {{ $conn['bytes_in'] }}B in / {{ $conn['bytes_out'] }}B out
                        </p>

                    </div>

                    {{-- RIGHT --}}
                    <div class="shrink-0 text-right">

                        {{-- STATUS --}}
                        <p class="text-[10px] font-semibold {{ $statusColor }}">
                            {{ $conn['status'] }}
                        </p>

                        {{-- RISK BADGE --}}
                        <span class="
                            mt-1 inline-block px-2 py-0.5 rounded text-[10px] border uppercase

                            @if($conn['risk'] == 'high')
                                border-red-500/30 text-red-400 bg-red-500/10
                            @elseif($conn['risk'] == 'medium')
                                border-yellow-500/30 text-yellow-400 bg-yellow-500/10
                            @else
                                border-green-500/30 text-green-400 bg-green-500/10
                            @endif
                        ">
                            {{ $conn['risk'] }}
                        </span>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>