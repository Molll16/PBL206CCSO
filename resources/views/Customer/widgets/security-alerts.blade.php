<div class="bg-gray-900 text-white p-4 rounded-2xl shadow-lg flex flex-col h-full max-h-[500px]">
    
    <style>
        .custom-scroll-clean::-webkit-scrollbar {
            display: none; /* Safari and Chrome */
        }
        .custom-scroll-clean {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>

    <div class="space-y-3 overflow-y-auto flex-grow pr-2 custom-scroll-clean">
        @foreach ($alerts as $alert)
            <div class="flex justify-between items-center bg-gray-800 p-3 rounded-xl">

                <div class="pr-4 min-w-0">
                    <p class="text-sm font-medium break-words">
                        {{ $alert['description'] }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $alert['agent']['name'] }} • {{ $alert['time'] }}
                    </p>
                </div>

                <div class="shrink-0 pl-2">
                    @if ($alert['level'] >= 10)
                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">CRITICAL</span>
                    @elseif ($alert['level'] >= 7)
                        <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs font-bold">HIGH</span>
                    @elseif ($alert['level'] >= 4)
                        <span class="bg-yellow-500 text-black px-2 py-1 rounded text-xs font-bold">MEDIUM</span>
                    @else
                        <span class="bg-green-500 text-black px-2 py-1 rounded text-xs font-bold">LOW</span>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>