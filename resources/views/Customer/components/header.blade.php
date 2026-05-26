<!-- BACKDROP -->
<div id="sidebar-backdrop"
     onclick="closeSidebar()"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 opacity-0 pointer-events-none transition-all duration-300">
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-56
              bg-black/40 backdrop-blur-2xl
              border-r border-white/10
              shadow-[0_0_40px_rgba(34,211,238,0.08)]
              z-40 flex flex-col
              -translate-x-full
              transition-transform duration-300 ease-in-out">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">

        <div class="flex items-center gap-3">
            <img src="/ob/logo.png"
                 class="w-7 drop-shadow-[0_0_10px_rgba(34,211,238,0.5)]"
                 alt="">

            <span class="text-[11px] tracking-wide leading-tight text-gray-200">
                Central Cyber <br> Security Office
            </span>
        </div>

        <button onclick="closeSidebar()"
                class="w-8 h-8 flex items-center justify-center rounded-lg
                       hover:bg-white/10
                       transition duration-300
                       text-gray-400 hover:text-cyan-400 text-lg">

            ‹

        </button>
    </div>

    <!-- NAV -->
    <nav class="flex-1 py-3 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ Route('dashboard-customer') }}"
           class="flex items-center gap-3 px-4 py-3 text-sm text-white
                  hover:bg-cyan-500/10
                  transition duration-300
                  border-l-[3px]
                  border-cyan-400
                  bg-cyan-500/5">

            <img src="/db/layout.png" class="w-4 opacity-90" alt="">
            Dashboard
        </a>

        <!-- Customization -->
        <div>

            <button onclick="toggleSubmenu('customization-menu', 'chevron-customization')"
                    class="w-full flex items-center justify-between
                           px-4 py-3 text-sm text-gray-300
                           hover:bg-white/[0.04]
                           transition duration-300
                           border-l-[3px]
                           border-transparent">

                <div class="flex items-center gap-3">
                    <img src="/db/custom.png" class="w-4 opacity-80" alt="">
                    Customization
                </div>

                <svg id="chevron-customization"
                     class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.5"
                     viewBox="0 0 24 24">

                    <path d="M19 9l-7 7-7-7"/>

                </svg>
            </button>

            <div id="customization-menu"
                 class="hidden bg-black/20 border-y border-white/5">

                <a href="{{ Route('pilih-dasbor') }}"
                   class="flex items-center pl-11 pr-4 py-2.5 text-xs
                          text-cyan-400
                          hover:bg-white/[0.04]
                          transition duration-300">

                    Visualize

                </a>

                <a href="#"
                   class="flex items-center pl-11 pr-4 py-2.5 text-xs
                          text-cyan-400
                          hover:bg-white/[0.04]
                          transition duration-300">

                    Discover

                </a>

            </div>

        <!-- Alerts -->
        <div>

            <button onclick="toggleSubmenu('agent-menu', 'chevron-agent')"
                    class="w-full flex items-center justify-between
                           px-4 py-3 text-sm text-gray-300
                           hover:bg-white/[0.04]
                           transition duration-300
                           border-l-[3px]
                           border-transparent">

                <div class="flex items-center gap-3">
                    <img src="/db/agent.png" class="w-4 opacity-80" alt="">
                    Alerts
                </div>

                <svg id="chevron-agent"
                     class="w-3.5 h-3.5 text-gray-500 transition-transform duration-300"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.5"
                     viewBox="0 0 24 24">

                    <path d="M19 9l-7 7-7-7"/>

                </svg>
            </button>

            <div id="agent-menu"
                 class="hidden bg-black/20 border-y border-white/5">

                <a href="{{ route('daftarlog') }}"
                   class="flex items-center pl-11 pr-4 py-2.5 text-xs
                          text-cyan-400
                          hover:bg-white/[0.04]
                          transition duration-300">

                    List Alerts

                </a>

            </div>
        </div>

    </nav>
</aside>

<!-- NAVBAR -->
<header class="sticky top-0 z-20
               bg-black/30 backdrop-blur-2xl
               border-b border-white/10
               shadow-[0_0_20px_rgba(0,0,0,0.25)]">

    <div class="flex items-center h-16">

        <!-- Sidebar Button -->
        <button onclick="openSidebar()"
                class="flex items-center justify-center
                       w-16 h-16
                       border-r border-white/10
                       hover:bg-white/[0.04]
                       transition duration-300">

            <div class="w-9 h-9
                        border border-white/20
                        rounded-xl
                        flex items-center justify-center
                        bg-white/[0.03]">

                <img src="/ob/sidebar.png" class="w-3.5 h-3.5" alt="">

            </div>
        </button>

        <!-- Home -->
        <a href="{{ route('dashboard-customer') }}"
           class="flex items-center justify-center
                  w-16 h-16
                  hover:bg-white/[0.04]
                  transition duration-300">

            <img src="/ob/home.png" class="w-8 opacity-90" alt="">

        </a>

        <!-- Logo -->
        <div class="flex items-center gap-3 px-2">

            <img src="/ob/logo.png"
                 class="w-7 drop-shadow-[0_0_10px_rgba(34,211,238,0.45)]"
                 alt="">

            <div>
                <p class="text-xs tracking-wide text-gray-200">
                    Central Cyber
                </p>

                <p class="text-xs tracking-wide text-gray-300">
                    Security Office
                </p>
            </div>
        </div>

        <div class="flex-1"></div>

        <!-- Right Menu -->
        <div class="flex items-center gap-3 pr-4">

            <!-- Manage -->
            <div class="relative">

                <button onclick="toggleManage()"
                        class="flex items-center gap-2
                               border border-white/10
                               bg-white/[0.03]
                               rounded-xl
                               px-4 py-2
                               text-sm
                               hover:bg-white/[0.06]
                               transition duration-300">

                    Manage

                    <img id="arrow-manage"
                         src="/ob/arrowdown.png"
                         class="w-3 transition-transform duration-200">

                </button>

                <!-- Dropdown -->
                <div id="manage-menu"
                     class="hidden absolute right-0 mt-2 w-40
                            bg-black/50 backdrop-blur-2xl
                            border border-white/10
                            rounded-2xl
                            shadow-[0_0_25px_rgba(0,0,0,0.35)]
                            overflow-hidden">

                    <a href="{{ Route('profile-overview') }}"
                       class="block px-4 py-3 text-xs text-center
                              hover:bg-white/[0.05]
                              transition duration-300">

                        Profile

                    </a>

                    <a href="#"
                       class="block px-4 py-3 text-xs text-center
                              hover:bg-white/[0.05]
                              transition duration-300">

                        Switch Agent

                    </a>

                    <a href="{{ Route('pilih-dasbor') }}"
                       class="block px-4 py-3 text-xs text-center
                              hover:bg-white/[0.05]
                              transition duration-300">

                        Custom Dashboard

                    </a>

                    <a href="{{ route('login') }}"
                       class="block px-4 py-3 text-xs text-center text-red-400
                              hover:bg-red-500/10
                              transition duration-300">

                        Logout

                    </a>

                </div>
            </div>

            <!-- Add -->
            <a href="{{ Route('pilih-dasbor') }}"
               class="flex items-center justify-center
                      w-10 h-10
                      rounded-xl
                      border border-cyan-400/20
                      bg-cyan-500/10
                      text-cyan-400 text-lg
                      hover:bg-cyan-500/20
                      hover:shadow-[0_0_15px_rgba(34,211,238,0.3)]
                      transition duration-300">

                +

            </a>

        </div>

    </div>
</header>