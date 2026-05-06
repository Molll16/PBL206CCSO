<div class="bg-gray-900 text-white p-4 rounded-2xl shadow-lg">
    <h2 class="text-lg font-semibold mb-4">🚨 Security Alerts</h2>

    <div class="space-y-3">
        @foreach ($alerts as $alert)
            <div class="flex justify-between items-center bg-gray-800 p-3 rounded-xl">
                
                <!-- LEFT -->
                <div>
                    <p class="text-sm font-medium">
                        {{ $alert['description'] }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $alert['agent'] }} • {{ $alert['time'] }}
                    </p>
                </div>

                <!-- RIGHT (LEVEL BADGE) -->
                <div>
                    @if ($alert['level'] >= 10)
                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">HIGH</span>
                    @elseif ($alert['level'] >= 5)
                        <span class="bg-yellow-500 text-black px-2 py-1 rounded text-xs">MEDIUM</span>
                    @else
                        <span class="bg-green-500 text-black px-2 py-1 rounded text-xs">LOW</span>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>