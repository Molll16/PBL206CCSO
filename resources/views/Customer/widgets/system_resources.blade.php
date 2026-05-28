<?php
// 1. Definisikan Dummy Data untuk System Resources
$resources = [
    [
        'label' => 'CPU',
        'value' => 65,
        'color' => 'bg-cyan-500'
    ],
    [
        'label' => 'RAM',
        'value' => 82,
        'color' => 'bg-amber-500'
    ],
    [
        'label' => 'DISK',
        'value' => 44,
        'color' => 'bg-emerald-500'
    ],
    [
        'label' => 'SWAP',
        'value' => 12,
        'color' => 'bg-indigo-500'
    ]
];
?>

<div class="flex flex-col h-full justify-between select-none">
    <div class="space-y-3 flex-1 flex flex-col justify-center">
        <?php foreach ($resources as $res): ?>
        <div class="flex items-center justify-between gap-4">

            <div class="w-12">
                <span class="text-gray-300 text-xs font-medium font-sans">
                    <?= htmlspecialchars($res['label']) ?>
                </span>
            </div>

            <div class="flex-1 bg-gray-700/30 h-2 rounded-full overflow-hidden">
                <div class="<?= $res['color'] ?> h-full rounded-full transition-all duration-500"
                    style="width: <?= $res['value'] ?>%;">
                </div>
            </div>

            <div class="w-10 text-right">
                <span class="text-gray-200 font-semibold text-xs font-mono">
                    <?= $res['value'] ?>%
                </span>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
</div>