<div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 max-w-sm select-none">
    <div class="mb-5">
        <h2 class="text-xl font-bold text-gray-200 tracking-wide">System resources</h2>
    </div>

    <div class="space-y-4">
        <?php foreach ($resources as $res): ?>
            <div class="flex items-center justify-between gap-4">
                
                <div class="w-16">
                    <span class="text-gray-300 text-base font-medium font-sans">
                        <?= htmlspecialchars($res['label']) ?>
                    </span>
                </div>

                <div class="flex-1 bg-gray-700/30 h-2.5 rounded-full overflow-hidden">
                    <div class="<?= $res['color'] ?> h-full rounded-full transition-all duration-500" 
                         style="width: <?= $res['value'] ?>%;">
                    </div>
                </div>

                <div class="w-12 text-right">
                    <span class="text-gray-200 font-semibold text-base font-mono">
                        <?= $res['value'] ?>%
                    </span>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>
