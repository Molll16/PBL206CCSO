<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        page: '#121318',
                        surface: '#1a1b23',
                        borderSubtle: '#262833',
                        textMain: '#f1f3f9',
                        textMuted: '#787f99',
                        brand: '#6366f1',
                        brandHover: '#4f46e5'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-page text-textMain flex flex-col min-h-screen md:h-screen md:overflow-hidden">

    @include('Customer.components.header')

    <div class="flex flex-col-reverse md:flex-row flex-1 bg-page overflow-y-auto md:overflow-hidden">

        <div
            class="flex-1 flex flex-col border-t md:border-t-0 md:border-r border-borderSubtle min-h-[450px] md:overflow-hidden">
            <div class="flex justify-between items-center px-4 py-3 border-b border-borderSubtle">
                <div class="flex items-center gap-2 min-w-0">
                    <span id="dashboard-title"
                        class="font-semibold cursor-pointer border-b-2 border-brand truncate text-sm md:text-base">
                        {{ $dashboard->nama_dasbor ?? 'Custom Dashboard' }}
                    </span>
                    <input type="hidden" id="dashboard-id" value="{{ $dashboard->id ?? '' }}">
                    <input id="dashboard-input"
                        class="hidden bg-page border border-brand px-2 py-0.5 text-sm rounded outline-none w-32 md:w-auto"
                        onblur="finishRename()" onkeydown="handleRenameKey(event)">
                    <i data-lucide="pencil" class="w-4 h-4 cursor-pointer opacity-60 hover:opacity-100 flex-shrink-0"
                        onclick="startRename()"></i>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button onclick="clearCanvas()"
                        class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded">
                        <i data-lucide="trash-2" class="w-5 h-5 text-red-400"></i>
                    </button>
                    <button onclick="saveLayout()"
                        class="bg-brand hover:bg-brandHover text-white px-3 py-1.5 rounded text-sm font-medium">
                        Save
                    </button>
                </div>
            </div>

            <div id="canvas-area" class="flex-1 p-4 overflow-y-auto no-scrollbar transition-colors"
                ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
                <div id="canvas-grid" class="grid grid-cols-4 md:grid-cols-12 gap-3 auto-rows-[80px] w-full"></div>
            </div>
        </div>

        <div
            class="w-full md:w-72 flex flex-col border-b md:border-b-0 md:border-l border-borderSubtle bg-[#1a1b23] max-h-[280px] md:max-h-none">
            <div
                class="p-3 border-b border-borderSubtle flex justify-between items-center bg-[#1a1b23] sticky top-0 z-10">
                <span class="font-semibold flex-1 text-center text-xs md:text-sm uppercase tracking-wider">Widget
                    Panel</span>
            </div>
            <div class="flex flex-col flex-1 overflow-hidden">
                <div id="widget-list"
                    class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-2 p-3 overflow-y-auto no-scrollbar"></div>
            </div>
        </div>
    </div>

    @include('Customer.components.footer')
    <div id="toast"
        class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-slate-900 border border-borderSubtle px-4 py-2 rounded opacity-0 transition z-50 text-sm shadow-xl pointer-events-none">
    </div>

    <script>
        // --- Variabel Global ---
        const FEATURES = @json($fitur);
        const COL_VALS = [3, 6, 9, 12];
        const ROW_VALS = [2, 4, 6, 8];

        let widgets = @json($hasil ?? []);
        let idCounter = widgets.length > 0 ? Math.max(...widgets.map(w => w.id)) : 0;
        let dragSourceType = null;
        let dragSourceData = null;
        let activeMaps = {};
        let toastTimer;

        // Helper deteksi jenis map
        const isMap = name => name.toLowerCase().includes('geoip') || name.toLowerCase().includes('map');

        // --- Inisialisasi ---
        init();
        function init() {
            renderPanel();
            renderCanvas();
        }

        // --- Render Panel Widget Samping ---
        function renderPanel() {
            const list = document.getElementById('widget-list');
            const used = new Set(widgets.map(w => w.fitur_id));
            const avail = FEATURES.filter(f => !used.has(f.id));

            if (!avail.length) {
                list.innerHTML = `<div class="col-span-3 text-center text-[11px] py-4 text-textMuted italic">Semua widget aktif</div>`;
                return;
            }

            list.innerHTML = avail.map(f => `
        <div class="bg-page/50 border border-borderSubtle rounded p-2 text-center text-[11px] cursor-grab hover:border-brand/50 select-none" 
             draggable="true" 
             onclick="handleWidgetClick(${f.id})" 
             ondragstart="handlePanelDragStart(event, ${f.id})">
            <div class="flex justify-center mb-1 pointer-events-none">
                <i data-lucide="box" class="w-5 h-5 text-textMuted"></i>
            </div>
            <span class="text-textMuted block truncate pointer-events-none" title="${f.nama_fitur}">${f.nama_fitur}</span>
        </div>
    `).join('');
            lucide.createIcons();
        }

        function handleWidgetClick(id) {
            const f = FEATURES.find(x => x.id === id);
            if (f) addWidget(f);
        }

        function handlePanelDragStart(e, id) {
            dragSourceType = 'panel';
            dragSourceData = FEATURES.find(x => x.id === id);
        }

        // --- Drag over area canvas ---
        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('canvas-area').classList.add('bg-brand/5');
        }

        function handleDragLeave() {
            document.getElementById('canvas-area').classList.remove('bg-brand/5');
        }

        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('canvas-area').classList.remove('bg-brand/5');
            if (dragSourceType === 'panel' && dragSourceData) {
                addWidget(dragSourceData);
            }
            dragSourceType = dragSourceData = null;
        }

        // --- Drag internal Canvas (Tukar Posisi) ---
        function handleCanvasDragStart(e, id) {
            dragSourceType = 'canvas';
            dragSourceData = id;
            e.stopPropagation();
        }

        function handleCanvasDrop(e, targetId) {
            e.preventDefault();
            e.stopPropagation();

            if (dragSourceType === 'canvas' && dragSourceData !== targetId) {
                const sIdx = widgets.findIndex(w => w.id === dragSourceData);
                const tIdx = widgets.findIndex(w => w.id === targetId);

                if (sIdx !== -1 && tIdx !== -1) {
                    // Tukar posisi array asli demi readability
                    const temp = widgets[sIdx];
                    widgets[sIdx] = widgets[tIdx];
                    widgets[tIdx] = temp;
                    renderCanvas();
                }
            }
            dragSourceType = dragSourceData = null;
        }

        // --- Render Canvas Grid ---
        function renderCanvas() {
            const grid = document.getElementById('canvas-grid');
            if (!widgets.length) {
                grid.innerHTML = `<div class="col-span-4 md:col-span-12 flex items-center justify-center border border-dashed border-borderSubtle text-textMuted rounded h-64 text-sm bg-surface/20">Drag widget ke sini (atau ketuk widget untuk menambah)</div>`;
                return;
            }

            grid.innerHTML = '';
            activeMaps = {};

            widgets.forEach(w => {
                const el = document.createElement('div');
                const isMobile = window.innerWidth < 768;

                el.className = "rounded flex flex-col min-w-0 shadow-lg cursor-move transition-transform duration-150 active:scale-[0.98]";
                el.style.background = '#1a1b23';
                el.style.border = '1px solid #262833';
                el.style.gridColumn = `span ${isMobile ? Math.min(w.colSpan, 4) : w.colSpan}`;
                el.style.gridRow = `span ${w.rowSpan}`;

                el.setAttribute('draggable', 'true');
                el.ondragstart = (e) => handleCanvasDragStart(e, w.id);
                el.ondragover = (e) => { e.preventDefault(); e.stopPropagation(); };
                el.ondrop = (e) => handleCanvasDrop(e, w.id);

                let bodyHtml = isMap(w.name)
                    ? `<div id="map-${w.id}" class="w-full h-full min-h-0 flex-1 rounded-b" draggable="false"></div>`
                    : `<div class="flex-1 flex items-center justify-center text-[11px] italic text-textMuted p-2 text-center truncate pointer-events-none">${w.name}</div>`;

                el.innerHTML = `
            <div class="flex justify-between items-center p-2 text-[10px] border-b border-borderSubtle text-textMuted" draggable="false">
                <span class="font-medium text-textMain truncate max-w-[45%] pointer-events-none">${w.name}</span>
                <div class="flex gap-1 items-center flex-shrink-0" draggable="false">
                    <button onclick="adjustSpan(${w.id},'col',-1)" class="hover:text-white p-0.5"><i data-lucide="minus" class="w-2.5 h-2.5"></i></button>
                    <span class="w-3 text-center font-bold text-textMain">${w.colSpan}</span>
                    <button onclick="adjustSpan(${w.id},'col',1)" class="hover:text-white p-0.5"><i data-lucide="plus" class="w-2.5 h-2.5"></i></button>
                    <span class="mx-0.5 text-gray-700">|</span>
                    <button onclick="adjustSpan(${w.id},'row',-1)" class="hover:text-white p-0.5"><i data-lucide="minus" class="w-2.5 h-2.5"></i></button>
                    <span class="w-3 text-center font-bold text-textMain">${w.rowSpan}</span>
                    <button onclick="adjustSpan(${w.id},'row',1)" class="hover:text-white p-0.5"><i data-lucide="plus" class="w-2.5 h-2.5"></i></button>
                    <button onclick="removeWidget(${w.id})" class="text-red-400 ml-1 hover:text-red-300 p-0.5"><i data-lucide="trash-2" class="w-2.5 h-2.5"></i></button>
                </div>
            </div>
            ${bodyHtml}
        `;
                grid.appendChild(el);

                // Render Leaflet Map jika tipe widget map
                if (isMap(w.name)) {
                    setTimeout(() => {
                        const mapCont = document.getElementById(`map-${w.id}`);
                        if (mapCont) {
                            const map = L.map(`map-${w.id}`, { zoomControl: true, attributionControl: true }).setView([0, 0], 1);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
                            setTimeout(() => map.invalidateSize(), 100);
                            activeMaps[w.id] = map;
                        }
                    }, 50);
                }
            });
            lucide.createIcons();
        }

        // --- Kontrol Manajemen Widget ---
        function addWidget(fitur) {
            widgets.push({ id: ++idCounter, fitur_id: fitur.id, name: fitur.nama_fitur, colSpan: 6, rowSpan: 4 });
            renderCanvas();
            renderPanel();
        }

        function removeWidget(id) {
            widgets = widgets.filter(w => w.id !== id);
            renderCanvas();
            renderPanel();
        }

        function clearCanvas() {
            if (confirm('Bersihkan semua widget?')) {
                widgets = [];
                renderCanvas();
                renderPanel();
            }
        }

        window.addEventListener('resize', renderCanvas);

        function adjustSpan(id, type, dir) {
            const w = widgets.find(x => x.id === id);
            if (!w) return;

            const vals = type === 'col' ? COL_VALS : ROW_VALS;
            const key = type === 'col' ? 'colSpan' : 'rowSpan';

            let idx = vals.indexOf(w[key]);
            w[key] = vals[Math.max(0, Math.min(vals.length - 1, idx + dir))];
            renderCanvas();
        }

        // --- Proses Simpan & Ubah Nama ---
        function saveLayout() {
            if (!widgets.length) return showToast('Pilih widget dulu');

            fetch('/customer/dashboard/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    dashboard_id: document.getElementById('dashboard-id').value,
                    nama_dashboard: document.getElementById('dashboard-title').innerText.trim(),
                    fitur: widgets.map(w => ({ fitur_id: w.fitur_id, kolom: w.colSpan, baris: w.rowSpan }))
                })
            })
                .then(() => {
                    showToast('Dashboard disimpan!');
                    setTimeout(() => window.location.href = '/customer/choosedashboard', 800);
                })
                .catch(() => showToast('Gagal menyimpan!'));
        }

        function showToast(msg) {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.classList.remove('opacity-0');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => el.classList.add('opacity-0'), 2000);
        }

        function startRename() {
            const t = document.getElementById('dashboard-title');
            const i = document.getElementById('dashboard-input');
            i.value = t.textContent.trim();
            t.classList.add('hidden');
            i.classList.remove('hidden');
            i.focus();
        }

        function finishRename() {
            const t = document.getElementById('dashboard-title');
            const i = document.getElementById('dashboard-input');
            if (i.value.trim()) t.textContent = i.value.trim();
            i.classList.add('hidden');
            t.classList.remove('hidden');
        }

        function handleRenameKey(e) {
            if (e.key === 'Enter') finishRename();
            if (e.key === 'Escape') {
                document.getElementById('dashboard-input').classList.add('hidden');
                document.getElementById('dashboard-title').classList.remove('hidden');
            }
        }
    </script>
</body>

</html>