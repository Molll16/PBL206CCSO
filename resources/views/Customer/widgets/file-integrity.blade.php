{{-- resources/views/Customer/widgets/file-integrity.blade.php --}}

<div class="h-full flex flex-col">
    <div id="fim-container"
        class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">
        <div class="flex flex-col items-center justify-center gap-2 py-4">
            <p class="text-xs text-gray-400 text-center animate-pulse">Scanning file integrity...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchFileIntegrity();
        // Cek update perubahan file secara berkala setiap 10 detik
        setInterval(fetchFileIntegrity, 10000);
    });

    function fetchFileIntegrity() {
        fetch("{{ route('widget.file-integrity') }}")
            .then(response => response.json())
            .then(result => {
                const container = document.getElementById('fim-container');

                if (!result.success || result.data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-6 text-gray-500 text-xs">
                            Tidak ada log perubahan berkas terdeteksi
                        </div>
                    `;
                    return;
                }

                container.innerHTML = ''; // Bersihkan data/loader lama

                result.data.forEach(file => {
                    // Tentukan warna badge berdasarkan status dari backend
                    let badgeHTML = '';
                    if (file.status === 'modified') {
                        badgeHTML = `<span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded text-[10px]">Modified</span>`;
                    } else if (file.status === 'deleted') {
                        badgeHTML = `<span class="bg-red-500/20 text-red-400 border border-red-500/30 px-2 py-0.5 rounded text-[10px]">Deleted</span>`;
                    } else if (file.status === 'added') {
                        badgeHTML = `<span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded text-[10px]">Added</span>`;
                    } else {
                        badgeHTML = `<span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">Intact</span>`;
                    }

                    const row = `
                        <div class="flex justify-between items-center bg-white/5 px-3 py-2 rounded-lg">
                            <div class="min-w-0 mr-3">
                                <p class="text-xs font-medium truncate text-white" title="\${file.path}">
                                    \${file.path}
                                </p>
                                <p class="text-[11px] text-gray-400 truncate">
                                    \${file.agent} • \${file.time}
                                </p>
                            </div>
                            <div class="shrink-0">
                                \${badgeHTML}
                            </div>
                        </div>
                    `;
                    container.innerHTML += row;
                });
            })
            .catch(error => {
                console.error("Error fetching FIM widget:", error);
            });
    }
</script>