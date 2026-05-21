{{-- resources/views/Customer/widgets/service-status.blade.php --}}

@php
    $services = [
        ['name' => 'nginx',    'port' => '80, 443', 'status' => 'running'],
        ['name' => 'mysql',    'port' => '3306',    'status' => 'running'],
        ['name' => 'redis',    'port' => '6379',    'status' => 'stopped'],
        ['name' => 'sshd',     'port' => '22',      'status' => 'running'],
        ['name' => 'fail2ban', 'port' => '-',       'status' => 'degraded'],
        ['name' => 'php-fpm',  'port' => '9000',    'status' => 'running'],
    ];
@endphp

<div class="h-full flex flex-col">

    <div class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        @foreach ($services as $service)

            <div class="flex justify-between items-center bg-white/5 px-3 py-2 rounded-lg">

                <div class="min-w-0 mr-3">
                    <p class="text-xs font-medium truncate text-white">
                        {{ $service['name'] }}
                    </p>
                    <p class="text-[11px] text-gray-400 truncate">
                        port {{ $service['port'] }}
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($service['status'] === 'running')
                        <span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">
                            Running
                        </span>
                    @elseif ($service['status'] === 'stopped')
                        <span class="bg-red-500/20 text-red-400 border border-red-500/30 px-2 py-0.5 rounded text-[10px]">
                            Stopped
                        </span>
                    @else
                        <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded text-[10px]">
                            Degraded
                        </span>
                    @endif
                </div>

            </div>

        @endforeach

    </div>

</div>