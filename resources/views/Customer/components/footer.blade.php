<footer class="bg-surface/90 border-t border-borderSubtle px-2 sm:px-3.5">
    <div class="max-w-7xl mx-auto text-[11px] text-textMuted">

        {{-- DESKTOP (lg+): Single row, tinggi sama kayak header --}}
        <div class="hidden lg:flex items-center h-11 sm:h-12 gap-3">
            <img src="/lp/logo.png" alt="Logo" class="h-4 transition-transform hover:scale-105">
            <span class="text-textMain font-semibold whitespace-nowrap">© 2026 CCSO, Inc.</span>
            <span class="text-borderSubtle">|</span>
            <span class="whitespace-nowrap">Kontak Hubungi</span>
            <div class="flex items-center gap-1.5 text-textMain whitespace-nowrap">
                <i data-lucide="phone" class="w-3.5 h-3.5 text-brand"></i>
                <span>+62 1234567890</span>
            </div>
            <div class="flex-1"></div>
            <div class="flex items-center gap-3 bg-page/50 px-3 py-1.5 rounded-md border border-borderSubtle">
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="music-2" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="hand-metal" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="mail" class="w-3.5 h-3.5"></i></a>
            </div>
            <div class="flex items-center border border-borderSubtle bg-page rounded-md overflow-hidden focus-within:border-brand/50 transition-colors">
                <input type="email" placeholder="Let Me Know Your Problem"
                    class="bg-transparent px-3 py-1.5 text-[11px] w-44 text-textMain focus:outline-none placeholder:text-textMuted">
                <button class="bg-brand/10 text-brand px-3 py-1.5 text-[11px] font-semibold hover:bg-brand hover:text-white transition-all border-l border-borderSubtle">
                    Send
                </button>
            </div>
        </div>

        {{-- TABLET (md): 2 kolom grid --}}
        <div class="hidden md:grid lg:hidden grid-cols-2 gap-3 py-3">
            <div class="flex items-center gap-2">
                <img src="/lp/logo.png" alt="Logo" class="h-4">
                <span class="text-textMain font-semibold">© 2026 CCSO, Inc.</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span>Kontak Hubungi</span>
                <div class="flex items-center gap-1.5 text-textMain">
                    <i data-lucide="phone" class="w-3.5 h-3.5 text-brand"></i>
                    <span>+62 1234567890</span>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-page/50 px-3 py-1.5 rounded-md border border-borderSubtle w-fit">
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="music-2" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="hand-metal" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="mail" class="w-3.5 h-3.5"></i></a>
            </div>
            <div class="flex items-center border border-borderSubtle bg-page rounded-md overflow-hidden focus-within:border-brand/50 transition-colors">
                <input type="email" placeholder="Let Me Know Your Problem"
                    class="bg-transparent px-3 py-1.5 text-[11px] w-full text-textMain focus:outline-none placeholder:text-textMuted">
                <button class="bg-brand/10 text-brand px-3 py-1.5 text-[11px] font-semibold hover:bg-brand hover:text-white transition-all border-l border-borderSubtle">
                    Send
                </button>
            </div>
        </div>

        {{-- MOBILE: Stacked vertikal --}}
        <div class="flex flex-col gap-3 py-3 md:hidden">
            <div class="flex items-center gap-2">
                <img src="/lp/logo.png" alt="Logo" class="h-4">
                <span class="text-textMain font-semibold">© 2026 CCSO, Inc.</span>
            </div>
            <div class="border-t border-borderSubtle"></div>
            <div class="flex flex-col gap-0.5">
                <span>Kontak Hubungi</span>
                <div class="flex items-center gap-1.5 text-textMain">
                    <i data-lucide="phone" class="w-3.5 h-3.5 text-brand"></i>
                    <span>+62 1234567890</span>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-page/50 px-3 py-1.5 rounded-md border border-borderSubtle w-fit">
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="music-2" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="hand-metal" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i></a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110"><i data-lucide="mail" class="w-3.5 h-3.5"></i></a>
            </div>
            <div class="flex items-center border border-borderSubtle bg-page rounded-md overflow-hidden focus-within:border-brand/50 transition-colors">
                <input type="email" placeholder="Let Me Know Your Problem"
                    class="bg-transparent px-3 py-1.5 text-[11px] flex-1 text-textMain focus:outline-none placeholder:text-textMuted">
                <button class="bg-brand/10 text-brand px-3 py-1.5 text-[11px] font-semibold hover:bg-brand hover:text-white transition-all border-l border-borderSubtle">
                    Send
                </button>
            </div>
        </div>

    </div>
</footer>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>