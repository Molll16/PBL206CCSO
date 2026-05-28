@php
$firewall_events = [

    [
        'title' => 'SSH Brute Force',
        'description' => 'Multiple failed login attempts detected',
        'severity' => 'HIGH',
        'ip' => '192.168.1.101',
        'port' => '22',
        'status' => 'BLOCKED',
        'color' => 'red'
    ],

    [
        'title' => 'Port Scanning',
        'description' => 'Suspicious port scanning activity detected',
        'severity' => 'MEDIUM',
        'ip' => '10.10.10.8',
        'port' => '443',
        'status' => 'SUSPICIOUS',
        'color' => 'yellow'
    ]
];
@endphp

<div class="h-full flex flex-col">

    <div class="flex-1 overflow-y-auto space-y-2 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        @foreach($firewall_events as $event)

            <div class="
                rounded-lg border px-3 py-2
                {{ $event['color'] == 'red'
                    ? 'border-red-500/30 bg-red-500/10'
                    : 'border-yellow-500/30 bg-yellow-500/10'
                }}
            ">

                <div class="flex justify-between items-center">

                    <div class="min-w-0 mr-3">

                        <p class="
                            text-xs font-medium truncate
                            {{ $event['color'] == 'red'
                                ? 'text-red-400'
                                : 'text-yellow-400'
                            }}
                        ">
                            {{ $event['title'] }}
                        </p>

                        <p class="text-[11px] text-gray-400 truncate">
                            {{ $event['ip'] }} • Port {{ $event['port'] }}
                        </p>

                    </div>

                    <div class="shrink-0 text-right">

                        <span class="
                            px-2 py-0.5 rounded text-[10px] border
                            {{ $event['color'] == 'red'
                                ? 'border-red-500/30 text-red-400 bg-red-500/10'
                                : 'border-yellow-500/30 text-yellow-400 bg-yellow-500/10'
                            }}
                        ">
                            {{ $event['severity'] }}
                        </span>

                        <p class="
                            text-[10px] mt-1
                            {{ $event['color'] == 'red'
                                ? 'text-red-300'
                                : 'text-yellow-300'
                            }}
                        ">
                            {{ $event['status'] }}
                        </p>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>