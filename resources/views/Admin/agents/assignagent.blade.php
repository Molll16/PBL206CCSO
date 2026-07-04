<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Agent</title>
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

<body class="min-h-screen bg-page text-textMain font-sans antialiased">

    @include('Admin.components.header-admin')

    <div class="p-6 max-w-[1400px] mx-auto">

        <main class="p-8 max-w-2xl mx-auto space-y-6">

            <div class="flex items-center gap-4">
                <a href="{{ route('agents-list') }}"
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand text-white hover:bg-brandHover transition-all group shrink-0 shadow-lg shadow-brand/20"
                    title="Back to Manage Users">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor"
                        stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold tracking-tight text-textMain">Configure Agent Assignment</h2>
                </div>
            </div>

            @if(session('success'))
                <div
                    class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm p-4 rounded-xl mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div
                    class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm p-4 rounded-xl mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 shrink-0"></span>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-surface border border-borderSubtle rounded-2xl p-8 shadow-sm">
                <form action="{{ route('assignagent.save') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-textMuted uppercase tracking-wider mb-2">Agent
                            Name</label>
                        <select name="agent_id" required
                            class="w-full rounded-lg px-4 py-2.5 text-sm border border-borderSubtle bg-page text-textMain outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200">
                            <option value="" disabled selected>Select Agent</option>

                            @foreach($agents as $agent)
                                <option value="{{ $agent['id'] }}">
                                    {{ $agent['name'] }} ({{ $agent['id'] }})
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-textMuted uppercase tracking-wider mb-2">Assigned
                            to</label>
                        <select name="user_id" required
                            class="w-full rounded-lg px-4 py-2.5 text-sm border border-borderSubtle bg-page text-textMain outline-none focus:border-brand focus:ring-1 focus:ring-brand transition duration-200">
                            <option value="" disabled selected>Select Customer</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="pt-4 border-t border-borderSubtle flex justify-end gap-3">
                        <a href="{{ route('agents-list') }}"
                            class="bg-surface border border-borderSubtle px-5 py-2.5 rounded-lg text-xs text-textMain font-medium hover:bg-borderSubtle transition-all flex items-center justify-center">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-brand hover:bg-brandHover text-white text-xs font-semibold px-5 py-2.5 rounded-lg shadow-lg shadow-brand/20 transition-all duration-200">
                            Assign Now
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Inisialisasi icon lucide
        lucide.createIcons();
    </script>

</body>

</html>