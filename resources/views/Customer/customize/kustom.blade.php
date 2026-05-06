  <!DOCTYPE html>
  <html lang="id">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CCSO Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite('resources/js/app.js')

  <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          base: "#2c2f36",
          deep: "#1f2228",
          card: "#2a2e36",
          border: "#3a3f47",
          accent: "#4d8fff",
          muted: "#9aa3b2"
        }
      }
    }
  }
  </script>

  <style>
    /* Menghilangkan scrollbar batang tapi tetap bisa scroll */
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
  </head>
  
@component('Customer.components.header') 
@endcomponent

  <body class="bg-deep text-white flex flex-col h-screen overflow-hidden">

  <div class="flex flex-1 bg-[#2B2D34] overflow-hidden">

  <div class="flex-1 flex flex-col border-r-2 overflow-hidden">

      <div class="flex justify-between items-center px-4 py-2 border-b border-border">

    <!-- Kiri -->
    <div class="flex items-center gap-2">

        <span id="dashboard-title"
              class="font-semibold cursor-pointer border-b-2">
            {{ $dashboard->nama_dasbor ?? 'Custom Dashboard' }}
        </span>

        <input type="hidden"
               id="dashboard-id"
               value="{{ $dashboard->id ?? '' }}">

        <input id="dashboard-input"
               class="hidden bg-deep px-2 py-0.5 text-sm rounded outline-none"
               onblur="finishRename()"
               onkeydown="handleRenameKey(event)">

        <img class="w-4 cursor-pointer"
             src="/custom/edit.png"
             onclick="startRename()">

    </div>

    <!-- Kanan -->
    <div class="flex items-center gap-2">

        <button onclick="toggleWidgetPanel()"
                class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded transition">
            <img src="/custom/fitur.png" alt="">
        </button>

        <button onclick="clearCanvas()"
                class="w-8 h-8 flex items-center justify-center hover:bg-white/10 rounded transition">
            <img src="/custom/hapus.png" alt="">
        </button>

        <button onclick="saveLayout()"
                class="border px-4 h-8 rounded-md hover:bg-white/10 transition">
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
      class="w-72 bg-base flex flex-col transition-all duration-300 ease-in-out border-l border-border">

      <div class="p-3 border-b-2 flex justify-between items-center cursor-default">
          <span class="font-semibold flex-1 text-center">Widget</span>   
      </div>

      <div id="widget-panel" class="flex flex-col flex-1 overflow-hidden">

          <div class="p-3 flex gap-2">
              <input type="text"
                    placeholder="Search"
                    class="flex-1 bg-[#2B2D34] border rounded-md px-2 py-1 text-sm ">

              <div class="relative">
                  <button onclick="toggleType(event)"
                          class="border px-2 py-1 text-sm rounded-md">
                      Type ▾
                  </button>

                  <div id="type-menu"
                      class="absolute right-0 mt-1 bg-card border hidden text-sm z-10">
                      <div class="px-3 py-1 hover:bg-accent cursor-pointer">All</div>
                      <div class="px-3 py-1 hover:bg-accent cursor-pointer">Chart</div>
                      <div class="px-3 py-1 hover:bg-accent cursor-pointer">Table</div>
                  </div>
              </div>
          </div>

          <div id="widget-list"
              class="grid grid-cols-3 gap-2 p-3 overflow-y-auto no-scrollbar">
          </div>

      </div>

  </div>

  </div>

  <footer class="bg-[#212121] px-10 py-4 border-t-2">
      <div id="footer">
        <div class="mt-2 px-1 flex flex-wrap items-center gap-4 text-sm text-gray-400">

          <img src="/lp/logo.png" class="h-10" alt="">

          <p class="text-white">© 2026 CCSO, Inc.</p>
          <img src="/lp/garis.png" class="h-5" alt="">

          <p class="text-white">Contact Us</p>
          <img src="/lp/garis.png" class="h-5" alt="">

          <div class="flex items-center gap-2">
            <img src="/lp/telp.png" class="h-5" alt="">
            <p>+62 1234567890</p>
          </div>

          <div class="flex items-center gap-6 ml-2">
            <img src="/lp/tt.png" class="h-5 cursor-pointer" alt="">
            <img src="/lp/ig.png" class="h-5 cursor-pointer" alt="">
            <img src="/lp/wa.png" class="h-5 cursor-pointer" alt="">
            <img src="/lp/email.png" class="h-5 cursor-pointer" alt="">
          </div>

          <div class="flex items-center ml-auto border border-gray-500 bg-white rounded overflow-hidden">
            <input type="email" placeholder="Sent to our Email..." class="px-3 py-1 text-sm w-56 text-gray-800 focus:outline-none">
            <button class="bg-blue-500 px-3 py-1 text-white text-sm hover:bg-blue-600">›</button>
          </div>

        </div>
      </div>
    </footer>

  <div id="toast"
      class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-card border border-accent px-4 py-2 rounded opacity-0 transition z-50">
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
                class="border rounded-md p-2 text-center text-xs cursor-pointer hover:border-white transition"
                draggable="true"
                onclick='addWidget(${JSON.stringify(f)})'
                ondragstart='dragSource=${JSON.stringify(f)}'>

                <div class="flex justify-center mb-1">
                    <img src="/custom/fitur.png">
                </div>

                ${f.nama_fitur}
            </div>
        `).join('');
}

/* ===============================
   CANVAS
================================= */
function renderCanvas(){

    const grid = document.getElementById('canvas-grid');

    if(widgets.length === 0){
        grid.innerHTML = `
            <div class="col-span-12 flex items-center justify-center border border-dashed border-white h-64 text-white">
                Drag widget ke sini
            </div>
        `;
        return;
    }

    grid.innerHTML = '';

    widgets.forEach(w => {

        const el = document.createElement('div');

        el.className = "bg-card border border-border rounded flex flex-col min-w-0";

        el.style.gridColumn = `span ${w.colSpan}`;
        el.style.gridRow = `span ${w.rowSpan}`;

        el.innerHTML = `
            <div class="flex justify-between p-2 text-[10px] border-b border-border">

                <span>${w.name}</span>

                <div class="flex gap-1 items-center">

                    <button onclick="adjustSpan(${w.id},'col',-1)">-</button>
                    <span class="w-3 text-center">${w.colSpan}</span>
                    <button onclick="adjustSpan(${w.id},'col',1)">+</button>

                    <button class="ml-1" onclick="adjustSpan(${w.id},'row',-1)">-</button>
                    <span class="w-3 text-center">${w.rowSpan}</span>
                    <button onclick="adjustSpan(${w.id},'row',1)">+</button>

                    <button onclick="removeWidget(${w.id})" class="text-red-400 ml-1">✕</button>

                </div>
            </div>

            <div class="flex-1 flex items-center justify-center text-muted text-xs italic">
                ${w.name}
            </div>
        `;

        grid.appendChild(el);
    });
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