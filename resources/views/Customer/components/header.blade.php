<div id="sidebar-backdrop" onclick="closeSidebar()"
  class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out">
</div>

<aside id="sidebar"
  class="fixed top-0 left-0 h-full w-[72vw] max-w-[240px] sm:w-56 md:w-60 bg-surface border-r border-borderSubtle z-40 flex flex-col -translate-x-full transition-transform duration-300 ease-in-out shadow-2xl">

  {{-- Sidebar Header --}}
  <div class="flex items-center justify-between h-11 sm:h-12 px-3 border-b border-borderSubtle bg-page/50">
    <div class="flex items-center gap-2 min-w-0">
      <img src="/ob/logo.png" class="w-5 flex-shrink-0 animate-pulse" alt="Logo">
      <span class="text-[10px] font-bold tracking-wider leading-tight text-textMain uppercase truncate">
        Central Cyber <br>
        <span class="text-brand">Security Office</span>
      </span>
    </div>
    <button onclick="closeSidebar()"
      class="w-6 h-6 flex items-center justify-center rounded-md flex-shrink-0 hover:bg-page/50 transition-colors text-textMuted hover:text-textMain ml-1">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  {{-- Sidebar Nav --}}
  <nav class="flex-1 py-2 overflow-y-auto space-y-0.5 px-1.5">

    <a href="{{ route('dashboard-customer') }}"
      class="flex items-center gap-2.5 px-3 py-2 rounded-md text-xs transition-all border-l-[3px] {{ request()->routeIs('dashboard-customer') ? 'border-brand bg-brand/10 text-brand font-medium' : 'border-transparent text-textMuted hover:bg-page hover:text-textMain' }}">
      <img src="/db/layout.png" class="w-3.5 opacity-80" alt="">
      Dashboard
    </a>

    <div>
      <button onclick="toggleSubmenu('customization-menu', 'chevron-customization')"
        class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs text-textMuted hover:bg-page hover:text-textMain transition-all border-l-[3px] border-transparent group">
        <div class="flex items-center gap-2.5">
          <img src="/db/custom.png" class="w-3.5 opacity-80" alt="">
          Customization
        </div>
        <svg id="chevron-customization" class="w-3 h-3 text-textMuted transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="customization-menu" class="hidden bg-page/50 rounded-md mx-1.5 mt-0.5 py-1 border border-borderSubtle/50">
        <a href="{{ route('pilih-dasbor') }}"
          class="flex items-center pl-8 pr-3 py-1.5 text-[11px] text-textMuted hover:text-brand hover:bg-surface transition-colors rounded mx-1.5">Visualize</a>
      </div>
    </div>

    <div>
      <button onclick="toggleSubmenu('agent-menu', 'chevron-agent')"
        class="w-full flex items-center justify-between px-3 py-2 rounded-md text-xs text-textMuted hover:bg-page hover:text-textMain transition-all border-l-[3px] border-transparent group">
        <div class="flex items-center gap-2.5">
          <img src="/db/agent.png" class="w-3.5 opacity-80" alt="">
          Alerts
        </div>
        <svg id="chevron-agent" class="w-3 h-3 text-textMuted transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="agent-menu" class="hidden bg-page/50 rounded-md mx-1.5 mt-0.5 py-1 border border-borderSubtle/50">
        <a href="{{ route('daftarlog') }}"
          class="flex items-center pl-8 pr-3 py-1.5 text-[11px] text-textMuted hover:text-brand hover:bg-surface transition-colors rounded mx-1.5">List Alerts</a>
      </div>
    </div>

  </nav>
</aside>

<header class="bg-surface/90 backdrop-blur-md border-b border-b-borderSubtle sticky top-0 z-20 shadow-sm">
  <div class="flex items-center h-11 sm:h-12 px-2 sm:px-3 gap-1 sm:gap-2 min-w-0">

    {{-- Sidebar Button --}}
    <button onclick="openSidebar()"
      class="flex-shrink-0 flex items-center justify-center p-1.5 rounded-md hover:bg-page border border-transparent hover:border-borderSubtle transition-all">
      <svg class="w-4 h-4 text-textMuted hover:text-textMain" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    {{-- Home (hidden di mobile) --}}
    <a href="{{ route('dashboard-customer') }}"
      class="flex items-center justify-center p-1.5 rounded-md hover:bg-page transition-colors flex-shrink-0">
      <img src="/ob/home.png" class="w-4 opacity-80" alt="Home">
    </a>

    {{-- Logo --}}
    <div class="flex items-center gap-1.5 border-l border-borderSubtle pl-2 flex-shrink-0">
      <img src="/ob/logo.png" class="w-4 sm:w-5 flex-shrink-0" alt="Logo">
      <div class="hidden sm:block leading-tight">
        <p class="text-[9px] font-bold tracking-widest uppercase text-textMain">Central Cyber</p>
        <p class="text-[9px] font-bold tracking-widest uppercase text-brand">Security Office</p>
      </div>
    </div>

    {{-- Spacer --}}
    <div class="flex-1 min-w-0"></div>

    {{-- Right Menu --}}
    <div class="flex items-center gap-1 sm:gap-1.5 min-w-0">

      {{-- Agent Switcher --}}
      <form action="{{ route('customer.agent.switch') }}" method="POST" id="form-header-switch-agent"
        class="flex items-center min-w-0">
        @csrf
        <select name="agent_id" onchange="document.getElementById('form-header-switch-agent').submit()"
          class="bg-page border border-borderSubtle rounded-md px-1 sm:px-2 py-1 text-[11px] font-semibold text-textMain hover:border-brand focus:outline-none cursor-pointer transition-all w-[55px] sm:w-[100px] md:w-[140px] lg:w-auto truncate">
          @if(isset($list_agen))
            @foreach($list_agen as $agen)
              <option value="{{ $agen->id_wazuh_agen }}" {{ session('active_wazuh_agent_id', $agen->id_wazuh_agen) == $agen->id_wazuh_agen ? 'selected' : '' }}>
                🖥️ {{ $agen->nama_agen ?? $agen->id_wazuh_agen }}
              </option>
            @endforeach
          @endif
        </select>
      </form>

      {{-- Manage Dropdown --}}
      <div class="relative id-dropdown-wrapper flex-shrink-0">
        <button onclick="toggleManage(event)"
          class="flex items-center gap-1 bg-page border border-borderSubtle rounded-md px-2 sm:px-3 py-1 text-[11px] font-semibold text-textMain hover:border-brand hover:text-brand transition-all whitespace-nowrap">
          Manage
          <i id="arrow-manage" data-lucide="chevron-down" class="w-3 h-3 text-textMuted transition-transform duration-300"></i>
        </button>

        <div id="manage-menu"
          class="hidden absolute right-0 mt-1.5 w-40 sm:w-44 bg-surface border border-borderSubtle rounded-lg shadow-2xl overflow-hidden py-1 z-50 animate-fade-in">
          <div class="px-3 py-2 border-b border-borderSubtle bg-page/50">
            <p class="text-[11px] text-textMain font-bold">Customer Account</p>
            <p class="text-[10px] text-textMuted truncate">{{ Auth::user()->username ?? 'customer@ccso.id' }}</p>
          </div>
          <a href="{{ route('profile-setting') }}"
            class="block px-3 py-2 text-[11px] text-textMuted hover:bg-page hover:text-brand transition-colors">Profile</a>
          <a href="{{ route('pilih-dasbor') }}"
            class="block px-3 py-2 text-[11px] text-textMuted hover:bg-page hover:text-brand transition-colors">Custom Dashboard</a>
          <div class="h-px bg-borderSubtle my-1"></div>
          <a href="{{ route('login') }}"
            class="block px-3 py-2 text-[11px] text-red-400 font-semibold hover:bg-red-500/10 transition-colors">Logout</a>
        </div>
      </div>

      {{-- Add Button --}}
      <a href="{{ Route('pilih-dasbor') }}"
        class="flex-shrink-0 flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 rounded-md border border-borderSubtle bg-brand/10 text-brand text-base font-bold hover:bg-brand/20 hover:border-brand transition-all">
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