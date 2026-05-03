<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSource Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/js/app.js')
</head>

<body class="bg-[#2B2D34] text-white min-h-screen flex flex-col">

@include('Customer.components.header')

<div class="flex-1 px-5 py-5">

    <!-- Judul -->
    <h1 class="text-2xl font-semibold border-b border-white inline-block pb-1 mb-8">
        OpenSource Dashboard
    </h1>

    <!-- Grid -->
    <div class="grid grid-cols-4 gap-6">

        @foreach($dashboard as $item)

        <div class="border border-white rounded-md h-52 p-4 flex flex-col justify-between">

            <!-- Nama Dashboard -->
            <div class="flex justify-center items-center flex-1 text-center text-lg font-medium px-2">
                {{ $item->nama_dasbor }}
            </div>

            <!-- Tombol -->
            <div class="flex justify-center gap-2 flex-wrap">

                <!-- View -->
                <a href="{{ route('dashboard.edit', $item->id) }}"
                   class="border border-white px-4 py-1 rounded text-sm hover:bg-white hover:text-black transition">
                    View
                </a>

                <!-- Status Aktif / Use -->
                @if($item->status_dasbor == 'aktif')

                    <button
                        class="bg-green-600 px-4 py-1 rounded text-sm cursor-default">
                        In Use
                    </button>

                @else

                    <form action="{{ route('dashboard.use', $item->id) }}"
                          method="POST">

                        @csrf

                        <button type="submit"
                            class="bg-blue-500 px-4 py-1 rounded text-sm hover:bg-blue-600 transition">
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

                        <button type="submit"
                            class="bg-red-600 px-4 py-1 rounded text-sm hover:bg-red-700 transition">
                            Delete
                        </button>

                    </form>

                @endif

            </div>

        </div>

        @endforeach


        <!-- Create New -->
        <a href="{{ route('kustomisasi') }}"
           class="border border-dashed border-white rounded-md h-52 flex flex-col items-center justify-center hover:bg-white/10 transition">

            <div class="text-5xl leading-none">+</div>
            <p class="mt-2 text-lg">Create New</p>

        </a>

    </div>

</div>

@include('Customer.components.footer')

</body>
</html>