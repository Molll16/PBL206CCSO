<div class="bg-gray-900 text-white p-4 rounded-2xl shadow-lg h-full flex flex-col justify-center">

    <style>
        .custom-scroll-clean::-webkit-scrollbar {
            display: none;
        }

        .custom-scroll-clean {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="grid grid-cols-3 gap-3 w-full text-center custom-scroll-clean">

        <div
            class="bg-gray-800/50 border border-green-500/20 rounded-xl py-5 flex flex-col items-center justify-center hover:bg-gray-800 transition-colors shadow-inner">
            <div class="relative flex h-3 w-3 mb-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </div>

            <p class="text-2xl font-bold text-green-400 leading-none">
                {{ $agentOnline ?? 0 }}
            </p>
            <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-wider font-medium">
                Online
            </p>
        </div>

        <div
            class="bg-gray-800/50 border border-red-500/20 rounded-xl py-5 flex flex-col items-center justify-center hover:bg-gray-800 transition-colors shadow-inner">
            <div class="relative flex h-3 w-3 mb-3">
                <span
                    class="relative inline-flex rounded-full h-3 w-3 bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
            </div>

            <p class="text-2xl font-bold text-red-400 leading-none">
                {{ $agentOffline ?? 0 }}
            </p>
            <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-wider font-medium">
                Offline
            </p>
        </div>

        <div
            class="bg-gray-800/50 border border-blue-500/20 rounded-xl py-5 flex flex-col items-center justify-center hover:bg-gray-800 transition-colors shadow-inner">
            <div class="text-sm mb-2.5 opacity-80">📦</div>

            <p class="text-2xl font-bold text-blue-400 leading-none">
                {{ $agentTotal ?? 0 }}
            </p>
            <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-wider font-medium">
                Total
            </p>
        </div>

    </div>
</div>