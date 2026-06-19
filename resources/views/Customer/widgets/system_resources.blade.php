<div class="flex flex-col h-full justify-between select-none">
    <div id="resource-container" class="space-y-3 flex-1 flex flex-col justify-center">
        <div class="flex flex-col items-center justify-center gap-2 py-4">
            <p class="text-xs text-textMuted text-center animate-pulse">Fetching resources data...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Jalankan fungsi pertama kali saat halaman selesai dimuat
        fetchSystemResources();

        // Lakukan sinkronisasi data realtime otomatis setiap 5 detik tanpa refresh halaman
        setInterval(fetchSystemResources, 5000);
    });

    function fetchSystemResources() {
        // Memanggil rute API yang sudah kita daftarkan di web.php
        fetch("{{ route('widget.system-resources') }}")
            .then(response => response.json())
            .then(result => {
                // Hentikan proses jika backend mengembalikan status gagal
                if (!result.success) {
                    showResourceError();
                    return;
                }

                const container = document.getElementById('resource-container');
                container.innerHTML = ''; // Bersihkan loader / data lama sebelum render ulang

                // Looping data array dari key 'data' milik response controller
                result.data.forEach(res => {
                    // Gunakan backtick (`) tanpa tanda backslash (\) sama sekali di dalam variabelnya
                    const row = `
                        <div class="flex items-center justify-between gap-4">
                            <div class="w-12">
                                <span class="text-gray-300 text-xs font-medium font-sans">
                                    ${res.label}
                                </span>
                            </div>
                
                            <div class="flex-1 bg-gray-700/30 h-2 rounded-full overflow-hidden">
                                <div class="${res.color} h-full rounded-full transition-all duration-500"
                                     style="width: ${res.value}%;">
                                </div>
                            </div>
                
                            <div class="w-10 text-right">
                                <span class="text-gray-200 font-semibold text-xs font-mono">
                                    ${res.value}%
                                </span>
                            </div>
                        </div>
                    `;
                    container.innerHTML += row;
                });
            })
            .catch(error => {
                console.error("Error fetching resource widget:", error);
                showResourceError();
            });
    }

    // Fungsi cadangan jika koneksi ke backend/API server terputus
    function showResourceError() {
        const container = document.getElementById('resource-container');
        container.innerHTML = `
            <div class="text-center py-2 text-red-400/80 text-xs flex items-center justify-center gap-1.5">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                <span>Gagal memuat metrik server</span>
            </div>
        `;
        // Re-inisialisasi ikon Lucide baru jika teks error ter-render
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>