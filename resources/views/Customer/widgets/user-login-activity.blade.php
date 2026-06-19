{{-- resources/views/Customer/widgets/user-login-activity.blade.php --}}

<div class="h-full flex flex-col">
    <div id="user-login-activity-container"
        class="flex-1 overflow-y-auto space-y-2 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">
        <div class="flex flex-col items-center justify-center py-6">
            <div class="w-5 h-5 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin mb-2"></div>
            <p class="text-xs text-gray-400">Fetching user logs...</p>
        </div>
    </div>
</div>

<script>
    (function () {
        function fetchUserLoginActivityData() {
            fetch("{{ route('widget.user-login-activity') }}?t=" + new Date().getTime(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
                .then(function (response) {
                    var contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response (status: ' + response.status + ')');
                    }
                    return response.json();
                })
                .then(function (result) {
                    var container = document.getElementById('user-login-activity-container');
                    if (!container) return;

                    if (!result.success || !result.data || result.data.length === 0) {
                        container.innerHTML =
                            '<div class="text-center py-8 text-gray-500 text-xs">Tidak ada aktivitas pengguna terdeteksi</div>';
                        return;
                    }

                    container.innerHTML = '';

                    result.data.forEach(function (activity) {
                        var cardClasses = 'rounded-lg border px-3 py-2 ';
                        var textClasses = 'text-xs font-medium truncate ';
                        var badgeClasses = 'px-2 py-0.5 rounded text-[10px] border ';

                        if (activity.status === 'danger') {
                            cardClasses += 'border-red-500/30 bg-red-500/10';
                            textClasses += 'text-red-400';
                            badgeClasses += 'border-red-500/30 text-red-400 bg-red-500/10';
                        } else if (activity.status === 'warning') {
                            cardClasses += 'border-yellow-500/30 bg-yellow-500/10';
                            textClasses += 'text-yellow-400';
                            badgeClasses += 'border-yellow-500/30 text-yellow-400 bg-yellow-500/10';
                        } else if (activity.status === 'success') {
                            cardClasses += 'border-green-500/30 bg-green-500/10';
                            textClasses += 'text-green-400';
                            badgeClasses += 'border-green-500/30 text-green-400 bg-green-500/10';
                        } else {
                            cardClasses += 'border-cyan-500/30 bg-cyan-500/10';
                            textClasses += 'text-cyan-400';
                            badgeClasses += 'border-cyan-500/30 text-cyan-400 bg-cyan-500/10';
                        }

                        var row =
                            '<div class="' + cardClasses + '">' +
                            '<div class="flex justify-between items-center">' +
                            '<div class="min-w-0 mr-3">' +
                            '<p class="' + textClasses + '">' + activity.user + '</p>' +
                            '<p class="text-[11px] text-gray-400 truncate" title="' + activity.activity + '">' + activity.activity + '</p>' +
                            '<p class="text-[10px] text-gray-500 truncate">' + activity.ip + ' • ' + activity.time + '</p>' +
                            '</div>' +
                            '<div class="shrink-0">' +
                            '<span class="' + badgeClasses + '">' + activity.status.toUpperCase() + '</span>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        container.innerHTML += row;
                    });
                })
                .catch(function (error) {
                    console.error('Error loading user activities:', error);
                    var container = document.getElementById('user-login-activity-container');
                    if (container) {
                        container.innerHTML =
                            '<div class="text-center py-8 text-red-500/60 text-xs">Gagal memuat data aktivitas</div>';
                    }
                });
        }

        // Langsung eksekusi tanpa menunggu DOMContentLoaded
        fetchUserLoginActivityData();
        setInterval(fetchUserLoginActivityData, 10000);

        document.addEventListener('agentChanged', function () {
            var container = document.getElementById('user-login-activity-container');
            if (container) {
                container.innerHTML =
                    '<div class="flex flex-col items-center justify-center py-6">' +
                    '<div class="w-5 h-5 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin mb-2"></div>' +
                    '<p class="text-xs text-gray-400">Switching agent log...</p>' +
                    '</div>';
            }
            fetchUserLoginActivityData();
        });
    })();
</script>