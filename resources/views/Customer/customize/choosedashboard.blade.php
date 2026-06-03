<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSource Dashboard</title>

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

<body class="text-textMain font-sans flex flex-col min-h-screen bg-page antialiased">

@include('Customer.components.header')

<div class="flex-1 px-6 py-6">

    <!-- Judul -->
    <div class="mb-8 flex items-center gap-3 animate-fade-in">
        <div class="w-[3px] h-6 rounded-full accent-bar"></div>
        <h1 class="text-textMain text-lg tracking-wide">OpenSource Dashboard</h1>
    </div>



    <!-- Grid -->
    <div class="grid grid-cols-4 gap-5">

        @foreach($dashboard as $item)

        <div class="dashboard-card rounded-xl h-52 p-4 flex flex-col justify-between animate-fade-in">

            <!-- Nama Dashboard -->
            <div class="flex justify-center items-center flex-1 text-center text-base font-medium px-2 text-textMain">
                {{ $item->nama_dasbor }}
            </div>

            <!-- Tombol -->
            <div class="flex justify-center gap-2 flex-wrap">

                <!-- View -->
                <a href="{{ route('dashboard.edit', $item->id) }}"
                   class="btn-view px-4 py-1.5 rounded-lg text-xs font-medium">
                    View
                </a>

                <!-- Status Aktif / Use -->
                @if($item->status_dasbor == 'aktif')

                    <button class="btn-inuse px-4 py-1.5 rounded-lg text-xs font-medium">
                        In Use
                    </button>

                @else

                    <form action="{{ route('dashboard.use', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-use px-4 py-1.5 rounded-lg text-xs font-medium">
                            Use
                        </button>
                    </form>

                @endif

                <!-- Delete hanya custom -->
                @if($item->jenis_dasbor == 'custom')

                    <form action="{{ route('dashboard.delete', $item->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus dashboard ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete px-4 py-1.5 rounded-lg text-xs font-medium">
                            Delete
                        </button>
                    </form>

                @endif

            </div>

        </div>

        @endforeach

        <!-- Create New -->
        <a href="{{ route('kustomisasi') }}"
           class="create-card rounded-xl h-52 flex flex-col items-center justify-center gap-2 animate-fade-in">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                 style="background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-sm text-textMuted">Create New</p>
        </a>

    </div>

</div>

@include('Customer.components.footer')

<script>
    lucide.createIcons();
</script>

</body>
</html>