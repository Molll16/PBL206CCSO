<div class="h-full flex items-center justify-center">

    <div class="grid grid-cols-3 gap-4 w-full text-center">

        <!-- Online -->
        <div class="bg-green-500/10 border border-green-400/30 rounded-xl py-4">
            <div class="text-2xl mb-1">🟢</div>
            <p class="text-xl font-bold text-green-400">
                {{ $agentOnline }}
            </p>
            <p class="text-xs text-gray-300 mt-1">
                Online
            </p>
        </div>

        <!-- Offline -->
        <div class="bg-red-500/10 border border-red-400/30 rounded-xl py-4">
            <div class="text-2xl mb-1">🔴</div>
            <p class="text-xl font-bold text-red-400">
                {{ $agentOffline }}
            </p>
            <p class="text-xs text-gray-300 mt-1">
                Offline
            </p>
        </div>

        <!-- Total -->
        <div class="bg-blue-500/10 border border-blue-400/30 rounded-xl py-4">
            <div class="text-2xl mb-1">📦</div>
            <p class="text-xl font-bold text-blue-400">
                {{ $agentTotal }}
            </p>
            <p class="text-xs text-gray-300 mt-1">
                Total
            </p>
        </div>

    </div>

</div>