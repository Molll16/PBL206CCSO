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
                <p class="text-lg font-bold text-red-500 mt-0.5">{{ $summary['active'] ?? 0 }}</p>
            </div>
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-yellow-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Pending</p>
                <p class="text-lg font-bold text-yellow-400 mt-0.5">{{ $summary['pending'] ?? 0 }}</p>
            </div>
            <div class="bg-gray-800 p-2.5 rounded-xl text-center border border-green-500/10">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Resolved</p>
                <p class="text-lg font-bold text-green-500 mt-0.5">{{ $summary['resolved'] ?? 0 }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Threat Categories</p>
            
            @forelse(($summary['categories'] ?? []) as $category)
                <div class="bg-gray-800/40 p-3 rounded-xl space-y-2 border border-gray-800/60">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-gray-300 truncate max-w-[170px]" title="{{ $category['name'] }}">
                            {{ $category['name'] }}
                        </span>
                        <span class="text-gray-400 font-semibold shrink-0">
                            {{ $category['count'] }} events ({{ $category['percentage'] }}%)
                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                        <div class="h-full rounded-full 
                            @if(($category['severity'] ?? 'low') === 'high') bg-red-500 
                            @elseif(($category['severity'] ?? 'low') === 'medium') bg-yellow-500 
                            @else bg-blue-500 @endif" 
                             style="width: {{ $category['percentage'] }}%">
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800/20 border border-dashed border-gray-700 p-4 rounded-xl text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        No recent threats detected on your agents.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="space-y-2 pt-1">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Latest Trend</p>
            
            @if(($summary['active'] ?? 0) > 0 || !empty($summary['categories']))
                <div class="bg-gray-800 p-3 rounded-xl flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2 h-2 rounded-full bg-red-400 shrink-0 animate-pulse"></span>
                        <span class="text-gray-300 truncate">
                            {{ isset($summary['categories'][0]['name']) ? $summary['categories'][0]['name'] : 'Security Alert Activity' }}
                        </span>
                    </div>
                    <span class="text-red-400 font-medium shrink-0 ml-2">Active</span>
                </div>
            @else
                <div class="bg-gray-800/40 p-3 rounded-xl flex items-center gap-2 text-xs text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                    <span>System state is stable</span>
                </div>
            @endif
        </div>

    </div>
</div>