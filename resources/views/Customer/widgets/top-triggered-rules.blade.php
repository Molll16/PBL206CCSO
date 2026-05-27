<div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 max-w-md select-none">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-200 tracking-wide">Top triggered rules</h2>
        <span class="text-sm text-gray-400 font-medium">Last 24h</span>
    </div>

    <div class="space-y-4">
        <?php foreach ($triggered_rules as $rule): ?>
            <div class="flex items-center justify-between gap-4">
                
                <div class="w-1/2">
                    <p class="text-gray-300 text-sm font-medium leading-tight">
                        <?= htmlspecialchars($rule['desc']) ?>
                    </p>
                </div>

                <div class="w-2/5 bg-gray-700/30 h-2.5 rounded-full overflow-hidden">
                    <div class="<?= $rule['color'] ?> h-full rounded-full transition-all duration-500" 
                         style="width: <?= $rule['w'] ?>;">
                    </div>
                </div>

                <div class="w-12 text-right">
                    <span class="text-gray-200 font-semibold text-base font-mono">
                        <?= $rule['count'] ?>
                    </span>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>
