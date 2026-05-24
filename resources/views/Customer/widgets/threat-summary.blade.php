@php
    $summary = [
        'active' => 18,
        'pending' => 7,
        'resolved' => 245,
        'categories' => [
            ['name' => 'SSH Brute Force Attempt', 'count' => 112, 'percentage' => 45, 'severity' => 'high'],
            ['name' => 'Web Application Attack (XSS)', 'count' => 62, 'percentage' => 25, 'severity' => 'high'],
            ['name' => 'Suspicious Sudo Elevation', 'count' => 38, 'percentage' => 15, 'severity' => 'medium'],
            ['name' => 'Outdated Software Vulnerability', 'count' => 25, 'percentage' => 10, 'severity' => 'low'],
            ['name' => 'Multiple Login Failures', 'count' => 12, 'percentage' => 5, 'severity' => 'medium'],
        ]
    ];
@endphp

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

    <div class="space-y-4 overflow-y-auto flex-grow pr-1 custom-scroll-clean">
        
        <div class="grid grid-cols-3 gap-2 shrink-0">
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-red-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Active</p>
                <p class="text-lg font-bold text-red-500 mt-0.5">{{ $summary['active'] }}</p>
            </div>
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-yellow-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Pending</p>
                <p class="text-lg font-bold text-yellow-400 mt-0.5">{{ $summary['pending'] }}</p>
            </div>
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-green-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Resolved</p>
                <p class="text-lg font-bold text-green-500 mt-0.5">{{ $summary['resolved'] }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Threat Categories</p>
            
            @foreach($summary['categories'] as $category)
                <div class="bg-gray-800/40 p-3 rounded-xl space-y-2 border border-gray-800/60">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-gray-300 truncate max-w-[170px]">{{ $category['name'] }}</span>
                        <span class="text-gray-400 font-semibold shrink-0">{{ $category['count'] }} events ({{ $category['percentage'] }}%)</span>
                    </div>
                    
                    <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                        <div class="h-full rounded-full 
                            @if($category['severity'] === 'high') bg-red-500 
                            @elseif($category['severity'] === 'medium') bg-yellow-500 
                            @else bg-blue-500 @endif" 
                             style="width: {{ $category['percentage'] }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-2 pt-1">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Latest Trend</p>
            <div class="bg-gray-800 p-3 rounded-xl flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-2 h-2 rounded-full bg-red-400 shrink-0 animate-pulse"></span>
                    <span class="text-gray-300 truncate">Brute Force Activity</span>
                </div>
                <span class="text-red-400 font-medium shrink-0 ml-2">↑ 14% This Week</span>
            </div>
        </div>

    </div>
</div>