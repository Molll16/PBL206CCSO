<?php
$triggered_rules = [
    ['desc' => 'SSHD Multiple Failed Login Attempts', 'count' => 1432, 'w' => '100%', 'color' => 'bg-red-500'],
    ['desc' => 'Web Server 404 Error Code Spikes', 'count' => 854, 'w' => '60%', 'color' => 'bg-orange-500'],
    ['desc' => 'Sudo Command Execution by Non-Root', 'count' => 312, 'w' => '22%', 'color' => 'bg-amber-500']
];
?>
<div class="flex flex-col h-full justify-between select-none">
    <div class="flex justify-between items-center mb-2 border-b border-gray-700/50 pb-1">
        <span class="text-[11px] text-gray-400 font-medium">Most Active Rules</span>
        <span class="text-[10px] text-gray-500 font-medium">Last 24h</span>
    </div>

    <div class="space-y-2 flex-1 flex flex-col justify-center">
        <?php foreach ($triggered_rules as $rule): ?>
        <div class="flex items-center justify-between gap-3 text-[11px]">
            <div class="w-1/2">
                <p class="text-gray-300 font-medium truncate" title="<?= htmlspecialchars($rule['desc']) ?>">
                    <?= htmlspecialchars($rule['desc']) ?>
                </p>
            </div>
            <div class="w-1/3 bg-gray-700/30 h-1.5 rounded-full overflow-hidden">
                <div class="<?= $rule['color'] ?> h-full rounded-full" style="width: <?= $rule['w'] ?>;"></div>
            </div>
            <div class="w-10 text-right">
                <span class="text-gray-200 font-semibold font-mono"><?= number_format($rule['count']) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>