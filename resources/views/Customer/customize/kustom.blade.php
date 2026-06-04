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

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

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

<body class="bg-page text-textMain flex flex-col h-screen overflow-hidden">

@include('Customer.components.header')

<div class="flex flex-1 bg-page overflow-hidden">

  <div class="flex-1 flex flex-col border-r border-borderSubtle overflow-hidden">

    <div class="flex justify-between items-center px-4 py-2 border-b border-borderSubtle">

      <!-- Kiri -->
      <div class="flex items-center gap-2">

        <span id="dashboard-title"
              class="font-semibold cursor-pointer border-b-2 border-brand">
            {{ $dashboard->nama_dasbor ?? 'Custom Dashboard' }}
        </span>

        <input type="hidden"
               id="dashboard-id"
               value="{{ $dashboard->id ?? '' }}">

        <input id="dashboard-input"
               class="hidden bg-page border border-brand px-2 py-0.5 text-sm rounded outline-none text-textMain"
               onblur="finishRename()"
               onkeydown="handleRenameKey(event)">

        <i data-lucide="pencil"
           class="w-4 h-4 cursor-pointer opacity-60 hover:opacity-100 transition-opacity"
           onclick="startRename()"></i>

      </div>

      <!-- Kanan -->
      <div class="flex items-center gap-2">

        <button onclick="clearCanvas()"
                class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded transition">
            <i data-lucide="trash-2" class="w-5 h-5 text-red-400"></i>
        </button>

        <button onclick="saveLayout()" class="btn-save flex items-center justify-center">
            Save
        </button>

      </div>

    </div>

    <div id="canvas-area"
        class="flex-1 p-4 overflow-y-auto no-scrollbar"
        ondragover="handleDragOver(event)"
        ondrop="handleDrop(event)">

        <div id="canvas-grid"
            class="grid grid-cols-12 gap-3 auto-rows-[80px] w-full">
        </div>

    </div>

  </div>

  <div id="right-sidebar-container"
      class="w-72 flex flex-col transition-all duration-300 ease-in-out border-l border-borderSubtle"
      style="background:#1a1b23">

      <div class="p-3 border-b border-borderSubtle flex justify-between items-center cursor-default">
          <span class="font-semibold flex-1 text-center text-textMain">Widget</span>
      </div>

      <div id="widget-panel" class="flex flex-col flex-1 overflow-hidden">

          <div id="widget-list"
              class="grid grid-cols-3 gap-2 p-3 overflow-y-auto no-scrollbar">
          </div>

      </div>

  </div>

</div>

@include('Customer.components.footer')

<div id="toast"
    class="fixed bottom-10 left-1/2 -translate-x-1/2 toast-box px-4 py-2 rounded opacity-0 transition z-50 text-textMain">
</div>


<script>
/* ===============================
   DASHBOARD CUSTOMIZE - FINAL DB VERSION
   Tinggal salin
================================= */

// DATA FITUR DARI LARAVEL
// Pastikan di blade ada variabel $fitur
const FEATURES = @json($fitur);

const COL_VALS = [3,6,9,12];
const ROW_VALS = [2,4,6,8];

let widgets = @json($hasil ?? []);
let idCounter = 0;
let dragSource = null;

init();

/* ===============================
   INIT
================================= */
function init(){
    renderPanel();
    renderCanvas();
    lucide.createIcons();
}

/* ===============================
   PANEL FITUR
================================= */
function renderPanel(){

    const list = document.getElementById('widget-list');

    const used = new Set(widgets.map(w => w.fitur_id));

    list.innerHTML = FEATURES
        .filter(f => !used.has(f.id))
        .map(f => `
            <div
                class="widget-card p-2 text-center text-xs cursor-pointer"
                draggable="true"
                onclick='addWidget(${JSON.stringify(f)})'
                ondragstart='dragSource=${JSON.stringify(f)}'>

                <div class="flex justify-center mb-1">
                    <i data-lucide="box" class="w-6 h-6 text-textMuted"></i>
                </div>

                <span style="color:#787f99">${f.nama_fitur}</span>
            </div>
        `).join('');

    lucide.createIcons();
}

/* ===============================
   CANVAS
================================= */
function renderCanvas(){

    const grid = document.getElementById('canvas-grid');

    if(widgets.length === 0){
        grid.innerHTML = `
            <div class="col-span-12 flex items-center justify-center canvas-empty h-64">
                Drag widget ke sini
            </div>
        `;
        return;
    }

    grid.innerHTML = '';

    widgets.forEach(w => {

        const el = document.createElement('div');

        el.className = "rounded flex flex-col min-w-0";
        el.style.background = '#1a1b23';
        el.style.border = '1px solid #262833';
        el.style.gridColumn = `span ${w.colSpan}`;
        el.style.gridRow = `span ${w.rowSpan}`;

        el.innerHTML = `
            <div class="flex justify-between p-2 text-[10px]" style="border-bottom:1px solid #262833; color:#787f99">

                <span style="color:#f1f3f9">${w.name}</span>

                <div class="flex gap-1 items-center">

                    <button onclick="adjustSpan(${w.id},'col',-1)" class="hover:text-white">
                        <i data-lucide="minus" class="w-3 h-3"></i>
                    </button>
                    <span class="w-3 text-center">${w.colSpan}</span>
                    <button onclick="adjustSpan(${w.id},'col',1)" class="hover:text-white">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                    </button>

                    <button class="ml-1 hover:text-white" onclick="adjustSpan(${w.id},'row',-1)">
                        <i data-lucide="minus" class="w-3 h-3"></i>
                    </button>
                    <span class="w-3 text-center">${w.rowSpan}</span>
                    <button onclick="adjustSpan(${w.id},'row',1)" class="hover:text-white">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                    </button>

                    <button onclick="removeWidget(${w.id})" class="text-red-400 ml-1 hover:text-red-300">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>

                </div>
            </div>

            <div class="flex-1 flex items-center justify-center text-xs italic" style="color:#787f99">
                ${w.name}
            </div>
        `;

        grid.appendChild(el);
    });

    lucide.createIcons();
}

/* ===============================
   ACTION
================================= */
function addWidget(fitur){

    widgets.push({
        id: ++idCounter,
        fitur_id: fitur.id,
        name: fitur.nama_fitur,
        colSpan: 6,
        rowSpan: 2
    });

    renderCanvas();
    renderPanel();
}

function removeWidget(id){

    widgets = widgets.filter(w => w.id !== id);

    renderCanvas();
    renderPanel();
}

function clearCanvas(){

    if(confirm('Bersihkan semua widget?')){

        widgets = [];

        renderCanvas();
        renderPanel();
    }
}

/* ===============================
   DRAG DROP
================================= */
function handleDragOver(e){
    e.preventDefault();
}

function handleDrop(e){

    e.preventDefault();

    if(dragSource){
        addWidget(dragSource);
        dragSource = null;
    }
}

/* ===============================
   RESIZE
================================= */
function adjustSpan(id, type, dir){

    const w = widgets.find(x => x.id === id);
    if(!w) return;

    const vals = type === 'col' ? COL_VALS : ROW_VALS;
    const key  = type === 'col' ? 'colSpan' : 'rowSpan';

    let idx = vals.indexOf(w[key]);

    idx = Math.max(0, Math.min(vals.length - 1, idx + dir));

    w[key] = vals[idx];

    renderCanvas();
}

/* ===============================
   SAVE KE LARAVEL DATABASE
================================= */
function saveLayout(){

    if(widgets.length === 0){
        showToast('Pilih widget dulu');
        return;
    }

    const namaDashboard =
        document.getElementById('dashboard-title').innerText.trim();
    const dashboardId =
        document.getElementById('dashboard-id').value;

    fetch('/customer/dashboard/save', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':
            document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({
            dashboard_id: dashboardId,
            nama_dashboard: namaDashboard,

            fitur: widgets.map(w => ({
                fitur_id: w.fitur_id,
                kolom: w.colSpan,
                baris: w.rowSpan
            }))
        })
    })

.then(async res => {

    const data = await res.json();

    showToast('Dashboard berhasil disimpan!');

    setTimeout(() => {
        window.location.href = '/customer/choosedashboard';
    }, 800);

})

    .catch(error => {
        console.error(error);
        showToast('Gagal menyimpan!');
    });
}

/* ===============================
   TOAST
================================= */
let toastTimer;

function showToast(msg){

    const el = document.getElementById('toast');

    el.textContent = msg;

    el.classList.remove('opacity-0');

    clearTimeout(toastTimer);

    toastTimer = setTimeout(() => {
        el.classList.add('opacity-0');
    }, 2000);
}
/* ===============================
   RENAME DASHBOARD
================================= */
function startRename(){

    const title = document.getElementById('dashboard-title');
    const input = document.getElementById('dashboard-input');

    input.value = title.textContent.trim();

    title.classList.add('hidden');
    input.classList.remove('hidden');

    input.focus();
}

function finishRename(){

    const title = document.getElementById('dashboard-title');
    const input = document.getElementById('dashboard-input');

    if(input.value.trim()){
        title.textContent = input.value.trim();
    }

    input.classList.add('hidden');
    title.classList.remove('hidden');
}

function handleRenameKey(e){

    if(e.key === 'Enter') finishRename();

    if(e.key === 'Escape'){
        document.getElementById('dashboard-input').classList.add('hidden');
        document.getElementById('dashboard-title').classList.remove('hidden');
    }
}

</script>

</body>
</html>