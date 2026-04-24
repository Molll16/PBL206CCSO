  <!DOCTYPE html>
  <html lang="id">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CCSO Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>

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

  <body class="bg-deep text-white flex flex-col h-screen overflow-hidden">

  <div id="sidebar-backdrop"
          onclick="closeSidebar()"
          class="fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity duration-300">
    </div>

    <aside id="sidebar"
          class="fixed top-0 left-0 h-full w-52 bg-[#1a1a1a] border-r border-gray-700 z-40 flex flex-col -translate-x-full transition-transform duration-300 ease-in-out">

      <div class="flex items-center justify-between h-16 px-3 border-b border-gray-700">
        <div class="flex items-center gap-4">
          <img src="/ob/logo.png" class="w-6" alt="">
          <span class="text-xs tracking-wide leading-tight">Central Cyber <br> Security Office</span>
        </div>
        <button onclick="closeSidebar()"
                class="w-7 h-7 flex items-center justify-center rounded hover:bg-gray-700 transition text-gray-400 hover:text-white text-base">
          ‹
        </button>
      </div>

      <nav class="flex-1 py-2 overflow-y-auto no-scrollbar">

        <a href="#"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-white hover:bg-[#2c2c2c] transition border-l-[3px] border-[#3282B8] bg-[#3282B8]/10">
          <img src="/db/layout.png" class="w-4" alt="">
          Dashboard
        </a>

        <div>
          <button onclick="toggleSubmenu('customization-menu', 'chevron-customization')"
                  class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-[#2c2c2c] transition border-l-[3px] border-transparent">
            <div class="flex items-center gap-3">
              <img src="/db/custom.png" class="w-4" alt="">
              Customization
            </div>
            <svg id="chevron-customization"
                class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div id="customization-menu" class="hidden bg-[#161616]">
            <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Visualize</a>
            <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Discover</a>
          </div>
        </div>

        <div>
          <button onclick="toggleSubmenu('agent-menu', 'chevron-agent')"
                  class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:bg-[#2c2c2c] transition border-l-[3px] border-transparent">
            <div class="flex items-center gap-3">
              <img src="/db/agent.png" class="w-4" alt="">
              Agent
            </div>
            <svg id="chevron-agent"
                class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div id="agent-menu" class="hidden bg-[#161616]">
            <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Information</a>
            <a href="#" class="flex items-center pl-11 pr-4 py-2 text-xs text-[#4DA8DA] hover:bg-[#2c2c2c] transition">Log</a>
          </div>
        </div>

      </nav>
    </aside>

    <header class="bg-[#212121] border-b-2 border-white sticky top-0 z-20">
      <div class="flex items-center h-16">

        <button onclick="openSidebar()"
                class="flex items-center justify-center w-16 h-16 border-r-4 border-white hover:bg-[#2c2c2c] transition">
          <div class="w-8 h-8 border-2 border-white rounded-lg flex items-center justify-center">
            <img src="/ob/sidebar.png" class="w-3 h-3" alt="">
          </div>
        </button>

        <button class="flex items-center justify-center w-16 h-16 hover:bg-[#2c2c2c] transition">
          <img src="/ob/home.png" class="w-8" alt="">
        </button>

        <div class="flex items-center gap-3 px-2">
          <img src="/ob/logo.png" class="w-6" alt="">
          <div>
            <p class="text-xs tracking-wide">Central Cyber</p>
            <p class="text-xs tracking-wide">Security Office</p>
          </div>
        </div>

        <div class="flex-1"></div>

        <div class="flex items-center gap-3 pr-3">

          <div class="relative">
            <button onclick="toggleManage()"
                    class="flex items-center gap-2 border border-white rounded-md px-4 py-2 text-sm hover:bg-[#2c2c2c] transition">
              Manage
              <img id="arrow-manage" src="/ob/arrowdown.png" class="w-3 transition-transform duration-200">
            </button>

            <div id="manage-menu"
                class="hidden absolute right-0 mt-2 w-32 bg-[#1e1e1e] border rounded-md shadow-xl overflow-hidden">
              <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Profile Settings</a>
              <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Agent</a>
              <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Custom Dashboard</a>
              <a href="#" class="block px-4 py-3 text-xs text-center hover:bg-[#2c2c2c] transition">Logout</a>
            </div>
          </div>

          <button class="flex items-center justify-center w-9 h-9 border border-white rounded-md text-lg hover:bg-[#2c2c2c] transition">
            +
          </button>
        </div>

      </div>
    </header>

  <div class="flex flex-1 bg-[#2B2D34] overflow-hidden">

  <div class="flex-1 flex flex-col border-r-2 overflow-hidden">

      <div class="flex justify-between px-4 py-2">
          <div class="flex items-center gap-2">
    <span id="dashboard-title"
          class="font-semibold cursor-pointer border-b-2">
      Custom Dashboard
    </span>


    <input id="dashboard-input"
          class="hidden bg-deep px-2 py-0.5 text-sm rounded outline-none"
          onblur="finishRename()"
          onkeydown="handleRenameKey(event)">
              <img class="w-4 cursor-pointer" src="/custom/edit.png" onclick="startRename()">
  </div>
          <div class="flex gap-2">
              <button onclick="toggleWidgetPanel()" class="w-8 px-2 hover:bg-white/10 rounded transition">
                  <img src="/custom/fitur.png" alt="Toggle Widget">
              </button>
              
              <button onclick="clearCanvas()" class="w-8 px-2 hover:bg-white/10 rounded transition">
                  <img src="/custom/hapus.png">
              </button>
              <button onclick="saveLayout()" class="border rounded-md px-3 hover:bg-white/10 transition">Save</button>
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

  // STATE
  const FEATURES = ['A','B','C','D','E','F','G','H'];
  const COL_VALS = [3,6,9,12];
  const ROW_VALS = [2,4,6,8];

  let widgets = [];
  let idCounter = 0;
  let dragSource = null;

  init();

  function init(){
      const saved = localStorage.getItem('layout');
      if(saved) {
          widgets = JSON.parse(saved);
          idCounter = widgets.length > 0 ? Math.max(...widgets.map(w => w.id)) : 0;
      }
      renderPanel();
      renderCanvas();
  }

  // PANEL
  function renderPanel(){
      const list = document.getElementById('widget-list');
      const used = new Set(widgets.map(w => w.name));

      list.innerHTML = FEATURES
          .map(f => `Feature ${f}`)
          .filter(n => !used.has(n))
          .map(name => `
              <div class="border rounded-md p-2 text-center text-xs cursor-pointer hover:border-white transition"
                  draggable="true"
                  onclick="addWidget('${name}')"
                  ondragstart="dragSource='${name}'">

                  <div class="flex justify-center mb-1">
                  <img src="/custom/fitur.png">
                  </div>
                  ${name}
              </div>
          `).join('');
  }

  // CANVAS
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

      widgets.forEach(w=>{
          const el = document.createElement('div');

          el.className = "bg-card border border-border rounded flex flex-col min-w-0";
          el.style.gridColumn = `span ${w.colSpan}`;
          el.style.gridRow = `span ${w.rowSpan}`;

          el.innerHTML = `
              <div class="flex justify-between p-2 text-[10px] border-b border-border">
                  <span>${w.name}</span>

                  <div class="flex gap-1 items-center">
                      <button class="hover:text-accent" onclick="adjustSpan(${w.id},'col',-1)">-</button>
                      <span class="w-3 text-center">${w.colSpan}</span>
                      <button class="hover:text-accent" onclick="adjustSpan(${w.id},'col',1)">+</button>

                      <button class="ml-1 hover:text-accent" onclick="adjustSpan(${w.id},'row',-1)">-</button>
                      <span class="w-3 text-center">${w.rowSpan}</span>
                      <button class="hover:text-accent" onclick="adjustSpan(${w.id},'row',1)">+</button>

                      <button onclick="removeWidget(${w.id})" class="text-red-400 ml-1">✕</button>
                  </div>
              </div>

              <div class="flex-1 flex items-center justify-center text-muted text-xs italic">
                  ${w.name} Content
              </div>
          `;

          grid.appendChild(el);
      });
  }

  // ACTION
  function addWidget(name){
      widgets.push({
          id: ++idCounter,
          name,
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
      if(confirm('Bersihkan semua widget?')) {
          widgets = [];
          renderCanvas();
          renderPanel();
      }
  }

  // DRAG
  function handleDragOver(e){ e.preventDefault(); }
  function handleDrop(e){
      e.preventDefault();
      if(dragSource){
          addWidget(dragSource);
          dragSource = null;
      }
  }

  // SIZE
  function adjustSpan(id, type, dir){
      const w = widgets.find(w=>w.id === id);
      if(!w) return;

      const vals = type === 'col' ? COL_VALS : ROW_VALS;
      const key = type === 'col' ? 'colSpan' : 'rowSpan';

      let idx = vals.indexOf(w[key]);
      idx = Math.max(0, Math.min(vals.length - 1, idx + dir));
      w[key] = vals[idx];

      renderCanvas();
  }

  // UI: PERUBAHAN FUNGSI BUKA/TUTUP (HILANG TOTAL)
  function toggleWidgetPanel(){
      const sidebar = document.getElementById('right-sidebar-container');
      
      // Kita gunakan class 'hidden' untuk menghilangkan total secara instan
      // atau 'w-0 overflow-hidden' untuk animasi menciut sampai hilang.
      if(sidebar.classList.contains('w-72')) {
          sidebar.classList.replace('w-72', 'w-0');
          sidebar.classList.add('overflow-hidden', 'border-none');
      } else {
          sidebar.classList.replace('w-0', 'w-72');
          sidebar.classList.remove('overflow-hidden', 'border-none');
      }
  }

  function toggleType(e){
      e.stopPropagation();
      document.getElementById('type-menu').classList.toggle('hidden');
  }

  // SAVE
  function saveLayout(){
      localStorage.setItem('layout', JSON.stringify(widgets));
      showToast('Saved!');
  }

  // TOAST
  let toastTimer;
  function showToast(msg){
      const el = document.getElementById('toast');
      el.textContent = msg;
      el.classList.remove('opacity-0');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(()=>el.classList.add('opacity-0'),2000);
  }

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

  //SIDEBAR AND MANAGE
  function openSidebar() {
    document.getElementById('sidebar').classList.replace('-translate-x-full', 'translate-x-0');
    document.getElementById('sidebar-backdrop').classList.replace('opacity-0', 'opacity-100');
    document.getElementById('sidebar-backdrop').classList.remove('pointer-events-none');
  }

  function closeSidebar() {
    document.getElementById('sidebar').classList.replace('translate-x-0', '-translate-x-full');
    document.getElementById('sidebar-backdrop').classList.replace('opacity-100', 'opacity-0');
    document.getElementById('sidebar-backdrop').classList.add('pointer-events-none');
  }

  function toggleSubmenu(menuId, chevronId) {
    document.getElementById(menuId).classList.toggle('hidden');
    document.getElementById(chevronId).classList.toggle('rotate-180');
  }

  function toggleManage() {
    const menu = document.getElementById('manage-menu');
    const arrow = document.getElementById('arrow-manage');
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
  }

  // Tutup dropdown jika klik di luar
  document.addEventListener('click', function(e) {
    const menu = document.getElementById('manage-menu');
    const typeMenu = document.getElementById('type-menu');
    
    if (menu && !menu.contains(e.target) && !e.target.closest('.relative')) {
      menu.classList.add('hidden');
      document.getElementById('arrow-manage').classList.remove('rotate-180');
    }
    if (typeMenu && !typeMenu.contains(e.target) && !e.target.closest('.relative')) {
      typeMenu.classList.add('hidden');
    }
  });

  </script>

  </body>
  </html>