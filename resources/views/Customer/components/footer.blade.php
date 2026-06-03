<footer class="bg-surface border-t border-borderSubtle px-6 py-4">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4 text-sm text-textMuted">
        
        <!-- Kiri: Logo & Copyright -->
        <div class="flex items-center gap-4">
            <img src="/lp/logo.png" alt="Logo" class="h-6 transition-transform hover:scale-105">
            <span class="text-textMain font-medium">© 2026 CCSO, Inc.</span>
            <div class="h-4 w-px bg-borderSubtle hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-brand"></i>
                <span class="text-textMain">+62 1234567890</span>
            </div>
        </div>

        <!-- Kanan: Social & Newsletter -->
        <div class="flex flex-wrap items-center gap-4">
            
            <!-- Social Icons -->
            <div class="flex items-center gap-4 bg-page/50 px-3 py-1.5 rounded-full border border-borderSubtle">
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110">
                    <i data-lucide="music-2" class="w-4 h-4"></i> <!-- TikTok placeholder or similar -->
                </a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110">
                    <i data-lucide="hand-metal" class="w-4 h-4"></i>
                </a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> <!-- WhatsApp -->
                </a>
                <a href="#" class="opacity-70 hover:opacity-100 transition-all hover:scale-110">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Mini Newsletter -->
            <div class="flex items-center bg-page border border-borderSubtle rounded-md overflow-hidden focus-within:border-brand/50 transition-all">
                <input 
                    type="email" 
                    placeholder="Let Me Know Your Problem" 
                    class="bg-transparent px-3 py-1.5 text-xs w-40 text-textMain outline-none placeholder:text-textMuted"
                >
                <button class="bg-brand/10 text-brand px-3 py-1.5 text-xs font-semibold hover:bg-brand hover:text-white transition-all border-l border-borderSubtle">
                    Send
                </button>
            </div>
        </div>
    </div>
</footer>

<script>
    // Ensure icons are rendered if this is loaded via AJAX or if global script missed it
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>