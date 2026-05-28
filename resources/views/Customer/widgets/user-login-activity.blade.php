@php
$userActivities = [

    [
        'user' => 'admin',
        'activity' => 'Failed login attempt',
        'ip' => '192.168.1.15',
        'status' => 'warning',
        'time' => '2 mins ago'
    ],

    [
        'user' => 'john.doe',
        'activity' => 'Password changed',
        'ip' => '10.10.10.20',
        'status' => 'success',
        'time' => '10 mins ago'
    ],

    [
        'user' => 'root',
        'activity' => 'Privilege escalation detected',
        'ip' => '172.16.0.5',
        'status' => 'danger',
        'time' => '25 mins ago'
    ],

    [
        'user' => 'guest',
        'activity' => 'Multiple session login',
        'ip' => '192.168.1.30',
        'status' => 'info',
        'time' => '1 hour ago'
    ]

];
@endphp

<div class="h-full flex flex-col">

    <div class="flex-1 overflow-y-auto space-y-2 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        @foreach($userActivities as $activity)

            <div class="
                rounded-lg border px-3 py-2

                @if($activity['status'] == 'danger')
                    border-red-500/30 bg-red-500/10
                @elseif($activity['status'] == 'warning')
                    border-yellow-500/30 bg-yellow-500/10
                @elseif($activity['status'] == 'success')
                    border-green-500/30 bg-green-500/10
                @else
                    border-cyan-500/30 bg-cyan-500/10
                @endif
            ">

                <div class="flex justify-between items-center">

                    <div class="min-w-0 mr-3">

                        <p class="
                            text-xs font-medium truncate

                            @if($activity['status'] == 'danger')
                                text-red-400
                            @elseif($activity['status'] == 'warning')
                                text-yellow-400
                            @elseif($activity['status'] == 'success')
                                text-green-400
                            @else
                                text-cyan-400
                            @endif
                        ">
                            {{ $activity['user'] }}
                        </p>

                        <p class="text-[11px] text-gray-400 truncate">
                            {{ $activity['activity'] }}
                        </p>

                        <p class="text-[10px] text-gray-500 truncate">
                            {{ $activity['ip'] }} • {{ $activity['time'] }}
                        </p>

                    </div>

                    <div class="shrink-0">

                        <span class="
                            px-2 py-0.5 rounded text-[10px] border

                            @if($activity['status'] == 'danger')
                                border-red-500/30 text-red-400 bg-red-500/10
                            @elseif($activity['status'] == 'warning')
                                border-yellow-500/30 text-yellow-400 bg-yellow-500/10
                            @elseif($activity['status'] == 'success')
                                border-green-500/30 text-green-400 bg-green-500/10
                            @else
                                border-cyan-500/30 text-cyan-400 bg-cyan-500/10
                            @endif
                        ">
                            {{ strtoupper($activity['status']) }}
                        </span>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>