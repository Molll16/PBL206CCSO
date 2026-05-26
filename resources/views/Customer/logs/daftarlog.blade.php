<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #2b2d34;
            color: #e5e7eb;
        }

        .bg-header {
            background-color: #1a1c1e;
        }

        .bg-card {
            background-color: #2b2d32;
        }

        .border-custom {
            border-color: #4a4e54;
        }

        .table-row-hover:hover {
            background-color: rgba(255, 255, 255, 0.03);
            transition: all 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</head>

<body class="min-h-screen bg-[#2B2D34]">

    @include('Customer.components.header')

    <div class="bg-[#2B2D34] px-6 flex items-center justify-between border-b-2 border-white animate-fade-in delay-1">
        <div class="flex gap-8">
            <a href="{{ route('daftarlog') }}"
                class="py-3 text-cyan-400 text-sm border-b-2 border-cyan-400 font-medium">View Logs</a>
        </div>
    </div>

    <main class="p-8 max-w-[1400px] mx-auto animate-fade-in">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <!-- CRITICAL -->
            <div class="bg-[#2B2D32] border border-red-700 rounded-xl p-5">
                <p class="text-sm text-red-400 font-medium">
                    Critical
                </p>
                <h2 class="text-3xl font-bold text-red-500 mt-2">
                    3
                </h2>
            </div>

            <!-- HIGH -->
            <div class="bg-[#2B2D32] border border-red-500 rounded-xl p-5">
                <p class="text-sm text-red-300 font-medium">
                    High
                </p>
                <h2 class="text-3xl font-bold text-red-400 mt-2">
                    5
                </h2>
            </div>

            <!-- MEDIUM -->
            <div class="bg-[#2B2D32] border border-yellow-500 rounded-xl p-5">
                <p class="text-sm text-yellow-300 font-medium">
                    Medium
                </p>
                <h2 class="text-3xl font-bold text-yellow-400 mt-2">
                    8
                </h2>
            </div>

            <!-- LOW -->
            <div class="bg-[#2B2D32] border border-green-500 rounded-xl p-5">
                <p class="text-sm text-green-300 font-medium">
                    Low
                </p>
                <h2 class="text-3xl font-bold text-green-400 mt-2">
                    12
                </h2>
            </div>

        </div>

        <div class="border border-white rounded-sm bg-transparent overflow-hidden">
            <div class="p-3 flex items-center justify-between border-b border-white">
                <div class="text-sm font-medium flex items-center gap-1">Alerts = 28</div>
            </div>

            <!-- FILTER & SEARCH -->
            <div class="p-4 border-b border-white flex flex-col md:flex-row gap-4 md:items-center md:justify-between">

                <!-- LEFT -->
                <div class="flex flex-col md:flex-row gap-3">

                    <!-- FILTER SEVERITY -->
                    <select class="bg-[#2B2D32]
                               border border-gray-600
                               rounded-lg
                               px-3 py-2
                               text-sm text-white
                               focus:outline-none
                               focus:border-cyan-400">

                        <option>All Severity</option>
                        <option>Critical</option>
                        <option>High</option>
                        <option>Medium</option>
                        <option>Low</option>

                    </select>

                    <!-- FILTER DATE -->
                    <input type="date" class="bg-[#2B2D32]
                               border border-gray-600
                               rounded-lg
                               px-3 py-2
                               text-sm text-white
                               focus:outline-none
                               focus:border-cyan-400">

                </div>

                <!-- RIGHT -->
                <div class="flex gap-3">

                    <!-- SEARCH -->
                    <input type="text" placeholder="Search alerts..." class="bg-[#2B2D32]
                               border border-gray-600
                               rounded-lg
                               px-3 py-2
                               text-sm text-white
                               placeholder-gray-400
                               focus:outline-none
                               focus:border-cyan-400">

                    <!-- REFRESH -->
                    <button onclick="location.reload()" class="bg-cyan-500
                               hover:bg-cyan-600
                               transition
                               px-4 py-2
                               rounded-lg
                               text-sm
                               font-medium
                               text-white
                               flex items-center gap-2">

                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>

                        Refresh

                    </button>

                </div>

            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-600 text-center text-gray-300 text-[10px] tracking-wider">
                            <th class="p-3 font-semibold">Time</th>
                            <th class="p-3 font-semibold">Severity</th>
                            <th class="p-3 font-semibold">Agent</th>
                            <th class="p-3 font-semibold">Event Type</th>
                            <th class="p-3 font-semibold">Description</th>
                            <th class="p-3 font-semibold">Source IP</th>
                            <th class="p-3 font-semibold">Status</th>
                            <th class="p-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50 text-center">

                        {{-- ROW 1 - Critical --}}
                        <tr class="table-row-hover">
                            <td class="p-3 text-gray-400">08:02</td>
                            <td class="p-3">
                                <span class="text-red-500 font-bold">Critical</span>
                            </td>
                            <td class="p-3">SRV-WEB-01</td>
                            <td class="p-3">Security Event</td>
                            <td class="p-3 max-w-xs truncate">Multiple failed SSH login attempts detected from external IP</td>
                            <td class="p-3">192.168.1.45</td>
                            <td class="p-3"><span class="text-red-400">Active</span></td>
                            <td class="p-3">
                                                        <!-- VIEW DETAIL BUTTON -->
                        <td class="p-3">
                            <button onclick="openModal()"
                                class="text-cyan-400 hover:text-cyan-300 hover:underline transition">
                                View Details
                            </button>
                        </td>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>

            <div class="p-4 flex items-center justify-between text-xs text-gray-400 border-t border-white">
                <div class="flex items-center gap-2">
                    Rows per page:
                    <select class="bg-transparent border border-white rounded px-1">
                        <option>10</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button class="hover:text-white"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                    <span class="text-white">1</span>
                    <span class="hover:text-white cursor-pointer">2</span>
                    <button class="hover:text-white"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    <!-- MODAL -->
<div id="alertModal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <!-- UBAH max-w-3xl jadi max-w-lg -->
    <div
        class="bg-[#2B2D32] border border-gray-700 rounded-2xl w-full max-w-lg max-h-[700px]  animate-fade-in overflow-y-auto [&::-webkit-scrollbar]:hidden [scrollbar-width:none]">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">

            <div class="flex items-center gap-3">

                <div
                    class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500 flex items-center justify-center">

                    <i data-lucide="shield-alert" class="w-5 h-5 text-red-500"></i>

                </div>

                <div>

                    <h2 class="text-base font-semibold text-white">
                        Failed SSH Login
                    </h2>

                    <p class="text-[11px] text-gray-400">
                        Security Event Detail
                    </p>

                </div>

            </div>

            <button onclick="closeModal()"
                class="text-gray-400 hover:text-white transition">

                <i data-lucide="x" class="w-4 h-4"></i>

            </button>

        </div>

        <!-- BODY -->
        <div class="p-5 space-y-5">

            <!-- STATUS -->
            <div class="flex items-center gap-2 flex-wrap">

                <span
                    class="px-2 py-1 rounded-full text-[10px] font-bold bg-red-500/10 border border-red-500 text-red-400">

                    HIGH

                </span>

                <span class="text-[11px] text-gray-500">
                    Rule ID : 5710
                </span>

                <span
                    class="px-2 py-1 rounded-full text-[10px] bg-green-500/10 border border-green-500 text-green-400">

                    Active

                </span>

            </div>

            <!-- INFO -->
            <div class="space-y-3 text-sm">

                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-500">Time</span>
                    <span class="text-white">2026-05-20 14:22</span>
                </div>

                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-500">Agent</span>
                    <span class="text-white">Ubuntu-Server-01</span>
                </div>

                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-500">Source IP</span>
                    <span class="text-cyan-400">103.21.1.10</span>
                </div>

                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-500">Port</span>
                    <span class="text-white">22</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Username</span>
                    <span class="text-white">root</span>
                </div>

            </div>

            <!-- DESCRIPTION -->
            <div>

                <h3 class="text-xs font-semibold text-cyan-400 mb-2">
                    Description
                </h3>

                <div class="bg-[#111827] border border-gray-700 rounded-lg p-3">

                    <p class="text-xs text-gray-300 leading-relaxed">

                        Multiple failed SSH login attempts detected from same IP.

                    </p>

                </div>

            </div>

            <!-- RAW LOG -->
            <div>

                <h3 class="text-xs font-semibold text-cyan-400 mb-2">
                    Raw Log
                </h3>

                <div
                    class="bg-black border border-gray-700 rounded-lg p-3 overflow-x-auto">

<pre class="text-green-400 text-[11px] leading-relaxed">
sshd[221]:
Failed password for root
from 103.21.1.10
port 51221 ssh2
</pre>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div
            class="px-5 py-4 border-t border-gray-700 flex items-center justify-end gap-2">

            <button onclick="closeModal()"
                class="px-3 py-2 rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700 transition text-xs">

                Close

            </button>

        </div>

    </div>

</div>
<script>

    lucide.createIcons();

    function openModal() {

        const modal = document.getElementById('alertModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        lucide.createIcons();
    }

    function closeModal() {

        const modal = document.getElementById('alertModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // close ketika klik background
    document.getElementById('alertModal').addEventListener('click', function(e) {

        if (e.target === this) {
            closeModal();
        }

    });

</script>
</body>

</html> 