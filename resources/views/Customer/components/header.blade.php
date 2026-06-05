<div id="sidebar-backdrop" onclick="closeSidebar()"
  class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out">
</div>

<aside id="sidebar"
  class="fixed top-0 left-0 h-full w-70 bg-surface border-r border-borderSubtle z-40 flex flex-col -translate-x-full transition-transform duration-300 ease-in-out shadow-2xl">

  <div class="flex items-center justify-between h-16 px-5 border-b border-borderSubtle bg-page/50">
    <div class="flex items-center gap-3">
      <img src="/ob/logo.png" class="w-6 animate-pulse" alt="Logo">
      <span class="text-xs font-bold tracking-wider leading-tight text-textMain uppercase">
        Central Cyber <br>
        <span class="text-brand">Security Office</span>
      </span>
    </div>
    <button onclick="closeSidebar()"
      class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-page/50 transition-colors text-textMuted hover:text-textMain">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <nav class="flex-1 py-4 overflow-y-auto space-y-1 px-2">

    <a href="{{ route('dashboard-customer') }}"
      class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all border-l-[3px] {{ request()->routeIs('dashboard-customer') ? 'border-brand bg-brand/10 text-brand font-medium' : 'border-transparent text-textMuted hover:bg-page hover:text-textMain' }}">
      <img src="/db/layout.png" class="w-4 opacity-80" alt="">
      Dashboard
    </a>

    <div>
      <button onclick="toggleSubmenu('customization-menu', 'chevron-customization')"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm text-textMuted hover:bg-page hover:text-textMain transition-all border-l-[3px] border-transparent group">
        <div class="flex items-center gap-3">
          <img src="/db/custom.png" class="w-4 opacity-80" alt="">
          Customization
        </div>
        <svg id="chevron-customization" class="w-4 h-4 text-textMuted transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div id="customization-menu" class="hidden bg-page/50 rounded-lg mx-2 mt-1 py-2 border border-borderSubtle/50">
        <a href="{{ route('pilih-dasbor') }}"
          class="flex items-center pl-10 pr-4 py-2 text-xs text-textMuted hover:text-brand hover:bg-surface transition-colors rounded-md mx-2">Visualize</a>
      </div>
    </div>

    <div>
      <button onclick="toggleSubmenu('agent-menu', 'chevron-agent')"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm text-textMuted hover:bg-page hover:text-textMain transition-all border-l-[3px] border-transparent group">
        <div class="flex items-center gap-3">
          <img src="/db/agent.png" class="w-4 opacity-80" alt="">
          Alerts
        </div>
        <svg id="chevron-agent" class="w-4 h-4 text-textMuted transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div id="agent-menu" class="hidden bg-page/50 rounded-lg mx-2 mt-1 py-2 border border-borderSubtle/50">
        <a href="{{ route('daftarlog') }}"
          class="flex items-center pl-10 pr-4 py-2 text-xs text-textMuted hover:text-brand hover:bg-surface transition-colors rounded-md mx-2">List
          Alerts</a>
      </div>
    </div>

  </nav>
</aside>

<header class="bg-surface/90 backdrop-blur-md border-b border-b-borderSubtle sticky top-0 z-20 shadow-sm">
  <div class="flex items-center h-16 px-4">

    <button onclick="openSidebar()"
      class="flex items-center justify-center p-2 rounded-lg hover:bg-page border border-transparent hover:border-borderSubtle transition-all mr-4">
      <svg class="w-6 h-6 text-textMuted hover:text-textMain" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <a href="{{ route('dashboard-customer') }}"
      class="hidden sm:flex items-center justify-center p-2 rounded-lg hover:bg-page transition-colors mr-4">
      <img src="/ob/home.png" class="w-5 opacity-80" alt="Home">
    </a>

    <div class="flex items-center gap-3 border-l border-borderSubtle pl-4">
      <img src="/ob/logo.png" class="w-6" alt="Logo">
      <div class="hidden sm:block">
        <p class="text-[10px] font-bold tracking-widest uppercase text-textMain">Central Cyber</p>
        <p class="text-[10px] font-bold tracking-widest uppercase text-brand">Security Office</p>
      </div>
    </div>

    <div class="flex-1"></div>

    <div class="flex items-center gap-4">

      <form action="{{ route('customer.agent.switch') }}" method="POST" id="form-header-switch-agent"
        class="flex items-center">
        @csrf
        <select name="agent_id" onchange="document.getElementById('form-header-switch-agent').submit()"
          class="bg-page border border-borderSubtle rounded-lg px-3 py-2 text-xs font-semibold text-textMain hover:border-brand focus:outline-none cursor-pointer transition-all">
      
          @if(isset($list_agen))
            @foreach($list_agen as $agen)
              <option value="{{ $agen->id_wazuh_agen }}" {{ session('active_wazuh_agent_id', $agen->id_wazuh_agen) == $agen->id_wazuh_agen ? 'selected' : '' }}>
                🖥️ {{ $agen->nama_agen ?? $agen->id_wazuh_agen }}
              </option>
            @endforeach
          @endif
        </select>
      </form>

      <div class="relative id-dropdown-wrapper">
        <button onclick="toggleManage(event)"
          class="flex items-center gap-2 bg-page border border-borderSubtle rounded-lg px-4 py-2 text-xs font-semibold text-textMain hover:border-brand hover:text-brand transition-all">
          Manage
          <i id="arrow-manage" data-lucide="chevron-down" class="w-3 h-3 text-white"></i>
        </button>

        <div id="manage-menu"
          class="hidden absolute right-0 mt-2 w-48 bg-surface border border-borderSubtle rounded-xl shadow-2xl overflow-hidden py-1 z-50 animate-fade-in">
          <div class="px-4 py-3 border-b border-borderSubtle bg-page/50">
            <p class="text-xs text-textMain font-bold">Customer Account</p>
            <p class="text-[10px] text-textMuted truncate">{{ Auth::user()->username ?? 'customer@ccso.id' }}</p>
          </div>
          <a href="{{ route('profile-setting') }}"
            class="block px-4 py-2.5 text-xs text-textMuted hover:bg-page hover:text-brand transition-colors">
            Profile</a>
          <a href="{{ route('pilih-dasbor') }}"
            class="block px-4 py-2.5 text-xs text-textMuted hover:bg-page hover:text-brand transition-colors">
            Custom Dashboard</a>
          <div class="h-px bg-borderSubtle my-1"></div>
          <a href="{{ route('login') }}"
            class="block px-4 py-2.5 text-xs text-red-400 font-semibold hover:bg-red-500/10 transition-colors">
            Logout</a>
        </div>
      </div>

      <a href="{{ Route('pilih-dasbor') }}"
        class="flex items-center justify-center w-9 h-9 rounded-lg border border-borderSubtle bg-brand/10 text-brand text-lg font-bold hover:bg-brand/20 hover:border-brand transition-all">
        +
      </a>
    </div>
  </div>
</header>

<script>
  function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    sidebar.classList.remove('-translate-x-full');
    backdrop.classList.remove('opacity-0', 'pointer-events-none');
    backdrop.classList.add('opacity-100', 'pointer-events-auto');
  }

  function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    sidebar.classList.add('-translate-x-full');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
    backdrop.classList.remove('opacity-100', 'pointer-events-auto');
  }

  function toggleSubmenu(menuId, chevronId) {
    const menu = document.getElementById(menuId);
    const chevron = document.getElementById(chevronId);

    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');
      chevron.classList.add('rotate-180');
    } else {
      menu.classList.add('hidden');
      chevron.classList.remove('rotate-180');
    }
  }

  function toggleManage(event) {
    if (event) event.stopPropagation();

    const menu = document.getElementById('manage-menu');
    const chevron = document.getElementById('arrow-manage');

    if (menu) {
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        if (chevron) chevron.classList.add('rotate-180');
      } else {
        menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
      }
    }
  }

  window.addEventListener('click', function (e) {
    const menu = document.getElementById('manage-menu');
    const chevron = document.getElementById('arrow-manage');
    const wrapper = document.querySelector('.id-dropdown-wrapper');

    if (menu && !menu.classList.contains('hidden')) {
      if (wrapper && !wrapper.contains(e.target)) {
        menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
      }
    }
  });
</script>