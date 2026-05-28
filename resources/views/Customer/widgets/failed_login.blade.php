<?php
$failed_data = [
    'count' => 1248,
    'timeline' => 'vs 412 entries yesterday',
    'status_tag' => 'Spike Detected'
];
?>
<div class="flex flex-col h-full justify-between select-none">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
            </path>
        </svg>
        <h2 class="text-sm font-medium text-gray-400 tracking-wide">Failed Logins Counter</h2>
    </div>

    <div class="my-auto">
        <span class="text-4xl font-bold text-amber-500 font-sans tracking-tight block">
            <?= number_format($failed_data['count']) ?>
        </span>
        <span class="text-gray-400 text-[11px] font-normal block mt-1">
            <?= htmlspecialchars($failed_data['timeline']) ?>
        </span>
    </div>

    <div class="self-start">
        <span
            class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-md text-[10px] font-semibold tracking-wide">
            <?= htmlspecialchars($failed_data['status_tag']) ?>
        </span>
    </div>
</div>