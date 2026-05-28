<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin CCSO</title>
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
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-textMain">Tugaskan Agen Ke Perangkat</h2>
                <p class="text-sm text-textMuted mt-1">Konfigurasikan penempatan kunci enkripsi agen SIEM baru.</p>
            </div>

            @if(session('success'))
            <div class="bg-green-600 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-600 text-white px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-surface border border-borderSubtle rounded-2xl p-8 shadow-sm">
                <form action="{{ route('assignagent.save') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-textMuted uppercase tracking-wider mb-2">Agent Name</label>
                        <select name="agent_id"
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
                      <label class="block text-xs font-semibold text-textMuted uppercase tracking-wider mb-2">Assigned to</label>
                        <select name="user_id"
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
                        <button type="button" onclick="history.back()"
                            class="bg-surface border border-borderSubtle px-5 py-2.5 rounded-lg text-xs text-textMain font-medium hover:bg-borderSubtle transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-brand hover:bg-brandHover text-white text-xs font-semibold px-5 py-2.5 rounded-lg shadow-lg shadow-brand/20 transition-all duration-200">
                            Tugaskan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    </div>

</body>

</html>