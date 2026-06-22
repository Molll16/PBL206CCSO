{{-- resources/views/Customer/widgets/file-integrity.blade.php --}}

<div class="h-full flex flex-col">
    <div id="fim-container"
        class="flex-1 overflow-y-auto space-y-1.5 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">
        <div class="flex flex-col items-center justify-center gap-2 py-4">
            <div class="w-5 h-5 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin mb-1"></div>
            <p class="text-xs text-gray-400 text-center">Scanning file integrity...</p>
        </div>
    </div>
</div>

<script>
    (function () {
        function fetchFileIntegrity() {
            // Ditambahkan timestamp agar browser tidak mengambil data lama dari cache
            fetch("{{ route('widget.file-integrity') }}?t=" + new Date().getTime(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
                .then(response => response.json())
                .then(result => {
                    const container = document.getElementById('fim-container');
                    if (!container) return;

                    // Validasi jika data gagal atau kosong
                    if (!result.success || !result.data || result.data.length === 0) {
                        container.innerHTML = `
                        <div class="text-center py-6 text-gray-500 text-xs">
                            Tidak ada log perubahan berkas terdeteksi
                        </div>
                    `;
                        return;
                    }

                    let htmlContent = '';

                    result.data.forEach(file => {
                        let badgeHTML = '';

                        // Menentukan badge berdasarkan status berkas
                        switch (file.status) {
                            case 'modified':
                                badgeHTML = `<span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded text-[10px]">MODIFIED</span>`;
                                break;
                            case 'deleted':
                                badgeHTML = `<span class="bg-red-500/10 text-red-400 border border-red-500/30 px-2 py-0.5 rounded text-[10px]">DELETED</span>`;
                                break;
                            case 'added':
                                badgeHTML = `<span class="bg-green-500/10 text-green-400 border border-green-500/30 px-2 py-0.5 rounded text-[10px]">ADDED</span>`;
                                break;
                            default:
                                badgeHTML = `<span class="bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 px-2 py-0.5 rounded text-[10px]">INTACT</span>`;
                        }

                        // Render string HTML menggunakan Template Literals (Backticks) secara bersih
                        htmlContent += `
                        <div class="flex justify-between items-center border border-gray-800 bg-gray-900/30 px-3 py-2 rounded-lg">
                            <div class="min-w-0 mr-3">
                                <p class="text-xs font-medium truncate text-gray-200" title="${file.path}">
                                    ${file.path}
                                </p>
                                <p class="text-[11px] text-gray-400 truncate">
                                    ${file.agent} • ${file.time}
                                </p>
                            </div>
                            <div class="shrink-0">
                                ${badgeHTML}
                            </div>
                        </div>
                    `;
                    });

                    container.innerHTML = htmlContent;
                })
                .catch(error => {
                    console.error("Error fetching FIM widget:", error);
                    const container = document.getElementById('fim-container');
                    if (container) {
                        container.innerHTML = `
                        <div class="text-center py-6 text-red-500/60 text-xs">
                            Gagal memuat data integritas berkas
                        </div>
                    `;
                    }
                });
        }

        // Jalankan saat pertama kali load
        fetchFileIntegrity();

        // Eksekusi berkala setiap 10 detik
        setInterval(fetchFileIntegrity, 10000);

        // Aksi interaktif ketika user mengubah dropdown device/agent
        document.addEventListener('agentChanged', function () {
            const container = document.getElementById('fim-container');
            if (container) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center gap-2 py-4">
                        <div class="w-5 h-5 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin mb-1"></div>
                        <p class="text-xs text-gray-400 text-center">Switching agent integrity...</p>
                    </div>
                `;
            }
            fetchFileIntegrity();
        });
    })();
</script>