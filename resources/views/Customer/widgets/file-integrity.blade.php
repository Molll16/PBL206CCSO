{{-- resources/views/Customer/widgets/file-integrity.blade.php --}}

@php
    $fileChanges = [
        ['path' => '/etc/passwd',          'agent' => 'Server01', 'time' => '2 mins ago',  'status' => 'modified'],
        ['path' => '/etc/ssh/sshd_config', 'agent' => 'Server01', 'time' => '15 mins ago', 'status' => 'modified'],
        ['path' => '/var/www/index.php',   'agent' => 'Server02', 'time' => '1 hour ago',  'status' => 'intact'],
        ['path' => '/bin/bash',            'agent' => 'Server02', 'time' => '3 hours ago', 'status' => 'intact'],
        ['path' => '/etc/crontab',         'agent' => 'Server01', 'time' => '5 hours ago', 'status' => 'deleted'],
        ['path' => '/var/log/auth.log',    'agent' => 'Server03', 'time' => '6 hours ago', 'status' => 'added'],
    ];
@endphp

<div class="h-full flex flex-col">

    <div class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        @foreach ($fileChanges as $file)

            <div class="flex justify-between items-center bg-white/5 px-3 py-2 rounded-lg">

                <div class="min-w-0 mr-3">
                    <p class="text-xs font-medium truncate text-white">
                        {{ $file['path'] }}
                    </p>
                    <p class="text-[11px] text-gray-400 truncate">
                        {{ $file['agent'] }} • {{ $file['time'] }}
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($file['status'] === 'modified')
                        <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded text-[10px]">
                            Modified
                        </span>
                    @elseif ($file['status'] === 'deleted')
                        <span class="bg-red-500/20 text-red-400 border border-red-500/30 px-2 py-0.5 rounded text-[10px]">
                            Deleted
                        </span>
                    @elseif ($file['status'] === 'added')
                        <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded text-[10px]">
                            Added
                        </span>
                    @else
                        <span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">
                            Intact
                        </span>
                    @endif
                </div>

            </div>

        @endforeach

    </div>

</div>