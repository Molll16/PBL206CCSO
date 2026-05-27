<div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 max-w-xs select-none">
    
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        <h2 class="text-lg font-medium text-gray-400 tracking-wide">Failed Logins</h2>
    </div>

    <div class="mb-1">
        <span class="text-5xl font-semibold text-yellow-800 font-sans tracking-tight">
            <?= number_format($failed_data['count']) ?>
        </span>
    </div>

    <div class="mb-4">
        <span class="text-gray-400 text-base font-medium">
            <?= htmlspecialchars($failed_data['timeline']) ?>
        </span>
    </div>

    <div class="inline-block">
        <span class="px-3 py-1 bg-yellow-950/40 border border-yellow-900/20 text-yellow-800 rounded-md text-sm font-semibold tracking-wide">
            <?= htmlspecialchars($failed_data['status_tag']) ?>
        </span>
    </div>

</div>
