<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Users</title>
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

    <style>
        #modal-overlay {
            opacity: 0; pointer-events: none;
            transition: opacity .2s ease;
        }
        #modal-overlay.open {
            opacity: 1; pointer-events: all;
        }
        #modal-box {
            transform: scale(.95) translateY(10px); opacity: 0;
            transition: transform .25s cubic-bezier(.4,0,.2,1), opacity .25s ease;
        }
        #modal-overlay.open #modal-box {
            transform: scale(1) translateY(0); opacity: 1;
        }
        .agent-scroll::-webkit-scrollbar { width: 4px; }
        .agent-scroll::-webkit-scrollbar-track { background: #121318; }
        .agent-scroll::-webkit-scrollbar-thumb { background: #262833; border-radius: 4px; }
        .agent-scroll::-webkit-scrollbar-thumb:hover { background: #6366f1; }
    </style>
</head>
<body class="min-h-screen bg-page text-textMain font-sans antialiased">

@include('Admin.components.header-admin')

{{-- ============================================================
     MODAL ASSIGN AGENT
     ============================================================ --}}
<div id="modal-overlay"
     class="fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background:rgba(0,0,0,.65); backdrop-filter:blur(4px)">

    <div id="modal-box"
         class="w-full max-w-md rounded-2xl overflow-hidden border border-borderSubtle shadow-2xl bg-surface">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-borderSubtle bg-page/40">
            <div>
                <p class="text-sm font-bold text-textMain">Assign Agent</p>
                <p id="modal-subtitle" class="text-xs mt-0.5 text-textMuted"></p>
            </div>
            <button onclick="closeModal()"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition hover:bg-borderSubtle text-textMuted hover:text-textMain">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        {{-- Current Agent --}}
        <div class="px-5 pt-4 pb-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-textMuted mb-2">Agent Aktif</p>

            <div id="current-agent-box"
                 class="hidden flex items-center gap-3 p-3 rounded-xl border border-borderSubtle bg-page/40">
                <span id="cur-dot" class="w-2 h-2 rounded-full flex-shrink-0"></span>
                <div class="flex-1 min-w-0">
                    <p id="cur-name" class="text-sm font-semibold text-textMain truncate"></p>
                    <p id="cur-meta" class="text-[11px] font-mono text-textMuted"></p>
                </div>
                <button onclick="doUnassign()"
                        class="text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-red-500/20 transition hover:bg-red-500/20"
                        style="background:rgba(239,68,68,.08); color:#f87171">
                    Lepas
                </button>
            </div>

            <div id="no-agent-box"
                 class="hidden flex items-center gap-2 p-3 rounded-xl border border-dashed border-borderSubtle text-xs text-textMuted">
                <i data-lucide="unlink" class="w-4 h-4 flex-shrink-0"></i>
                <span>Belum ada agent yang di-assign ke user ini</span>
            </div>
        </div>

        {{-- Agent List --}}
        <div class="px-5 pb-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-textMuted mb-2 mt-3">Pilih Agent</p>

            <div class="relative mb-3">
                <i data-lucide="search"
                   class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-textMuted pointer-events-none"></i>
                <input id="agent-search" type="text"
                       placeholder="Cari nama atau IP..."
                       oninput="filterAgents(this.value)"
                       class="w-full pl-8 pr-4 py-2 text-xs rounded-lg border border-borderSubtle bg-page text-textMain placeholder-textMuted focus:outline-none focus:border-brand/50 transition">
            </div>

            <div id="agent-list" class="agent-scroll space-y-1.5 max-h-48 overflow-y-auto pr-0.5"></div>
            <p id="no-result" class="hidden text-center text-xs text-textMuted py-4">Tidak ada agent ditemukan</p>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-borderSubtle bg-page/20">
            <button onclick="closeModal()"
                    class="px-4 py-2 rounded-lg text-xs font-medium border border-borderSubtle text-textMuted hover:bg-borderSubtle hover:text-textMain transition">
                Batal
            </button>
            <button onclick="doAssign()"
                    class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-brand hover:bg-brandHover shadow-lg shadow-brand/20 transition-all duration-200">
                Simpan Assign
            </button>
        </div>

    </div>
</div>
{{-- END MODAL --}}

{{-- Toast --}}
<div id="toast"
     class="fixed bottom-6 right-6 z-[60] flex items-center gap-2 px-4 py-3 rounded-xl text-xs font-semibold text-white border border-borderSubtle shadow-lg bg-surface transition-all duration-300"
     style="opacity:0; transform:translateY(8px)">
    <span id="toast-icon" class="w-4 h-4"></span>
    <span id="toast-msg"></span>
</div>


{{-- ============================================================
     MAIN CONTENT
     ============================================================ --}}
<div class="p-6 max-w-[1400px] mx-auto">
    <main class="p-8 max-w-7xl mx-auto space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
        
            <div
                class="bg-[#222428] border border-white/10 p-5 rounded-xl hover:border-cyan-400/30 transition-all duration-300 group">
                <p
                    class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-gray-300 transition-colors">
                    Total User
                </p>
                <h2 class="text-4xl font-bold mt-2 text-white tracking-tight">
                    {{ $totalUsers }}
                </h2>
            </div>
        
            <div
                class="bg-[#222428] border border-white/10 p-5 rounded-xl hover:border-cyan-400/30 transition-all duration-300 group">
                <p
                    class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-gray-300 transition-colors">
                    Agent Assigned
                </p>
                <h2 class="text-4xl font-bold mt-2 text-cyan-400 tracking-tight">
                    {{ $totalAssignedAgents }}
                </h2>
            </div>
        
        </div>


        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-textMain">Manajemen User</h2>
                <p class="text-sm text-textMuted mt-1">Kelola hak akses dan akun pengguna</p>
            </div>
            <a href="{{ route('adduser') }}">
                <button class="bg-brand hover:bg-brandHover text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-lg shadow-brand/20 transition-all duration-200">
                    + Tambah User Baru
                </button>
            </a>
        </div>

        <div class="bg-surface border border-borderSubtle rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="border-b border-borderSubtle bg-page/30 text-[11px] font-bold uppercase tracking-wider text-textMuted">
                            <th class="py-3 px-5">ID</th>
                            <th class="py-3 px-5">Username</th>
                            <th class="py-3 px-5">Email</th>
                            <th class="py-3 px-5">Role</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-borderSubtle text-xs text-textMain">
                        @foreach($users as $user)
                        <tr class="hover:bg-page/40 transition-colors">
                            <td class="py-3.5 px-5 text-textMuted font-mono">#{{ $user->id }}</td>
                            <td class="py-3.5 px-5 font-semibold text-textMain">{{ $user->username }}</td>
                            <td class="py-3.5 px-5 text-textMuted">{{ $user->email }}</td>
                            <td class="py-3.5 px-5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-borderSubtle border border-borderSubtle text-textMain">
                                    {{ $user->role ?? 'User' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right space-x-3">

                                {{-- Tombol Edit → buka modal, kirim data user via parameter --}}
                                <button type="button"
                                        onclick="openModal({{ $user->id }}, '{{ addslashes($user->username) }}', {{ $user->agent_id ?? 'null' }})"
                                        class="text-brand hover:underline font-medium">
                                    Edit
                                </button>

                                <form action="{{ route('customers.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-400 hover:underline font-medium"
                                            onclick="return confirm('Hapus user ini?')">Hapus</button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>


<script>
// ============================================================
// DUMMY DATA AGENT — ganti dengan $agents dari backend saat siap
// ============================================================
const ALL_AGENTS = [
    { id: 'agt-001', name: 'web-server-01',  ip: '192.168.1.10', status: 'active',  os: 'Ubuntu 22.04 LTS' },
    { id: 'agt-002', name: 'db-server-main', ip: '192.168.1.20', status: 'active',  os: 'CentOS 8' },
    { id: 'agt-003', name: 'app-node-03',    ip: '192.168.1.31', status: 'offline', os: 'Debian 11' },
    { id: 'agt-004', name: 'proxy-01',       ip: '10.0.0.5',     status: 'active',  os: 'Ubuntu 20.04 LTS' },
    { id: 'agt-005', name: 'backup-srv',     ip: '10.0.0.12',    status: 'offline', os: 'Windows Server 2019' },
    { id: 'agt-006', name: 'monitor-node',   ip: '192.168.2.1',  status: 'active',  os: 'Ubuntu 22.04 LTS' },
];

// State modal
let currentUserId   = null;
let currentAgentId  = null;  // agent yang sedang aktif pada user ini
let selectedAgentId = null;  // agent yang dipilih di list
let filteredAgents  = [...ALL_AGENTS];

// ============================================================
// OPEN / CLOSE
// ============================================================
function openModal(userId, username, agentId) {
    currentUserId   = userId;
    currentAgentId  = agentId;
    selectedAgentId = agentId;

    document.getElementById('modal-subtitle').textContent =
        username + ' · #' + userId;

    // Tampilkan current agent
    const agent  = ALL_AGENTS.find(a => String(a.id) === String(agentId));
    const curBox = document.getElementById('current-agent-box');
    const noBox  = document.getElementById('no-agent-box');

    if (agent) {
        curBox.classList.remove('hidden');
        noBox.classList.add('hidden');
        document.getElementById('cur-dot').style.background =
            agent.status === 'active' ? '#4ade80' : '#f87171';
        document.getElementById('cur-name').textContent = agent.name;
        document.getElementById('cur-meta').textContent =
            agent.ip + ' · ' + agent.os;
    } else {
        curBox.classList.add('hidden');
        noBox.classList.remove('hidden');
    }

    // Reset search & render list
    document.getElementById('agent-search').value = '';
    filteredAgents = [...ALL_AGENTS];
    renderAgentList();

    document.getElementById('modal-overlay').classList.add('open');
    lucide.createIcons();
}

function closeModal() {
    document.getElementById('modal-overlay').classList.remove('open');
}

// ============================================================
// FILTER & RENDER LIST
// ============================================================
function filterAgents(q) {
    const s = q.toLowerCase();
    filteredAgents = ALL_AGENTS.filter(a =>
        a.name.toLowerCase().includes(s) || a.ip.includes(s));
    renderAgentList();
}

function renderAgentList() {
    const list  = document.getElementById('agent-list');
    const noMsg = document.getElementById('no-result');

    if (!filteredAgents.length) {
        list.innerHTML = '';
        noMsg.classList.remove('hidden');
        return;
    }
    noMsg.classList.add('hidden');

    list.innerHTML = filteredAgents.map(a => {
        const isCur = String(a.id) === String(currentAgentId);
        const isSel = String(a.id) === String(selectedAgentId);
        const dotBg = isSel ? '#6366f1'
                    : (a.status === 'active' ? '#4ade80' : '#f87171');
        const cardStyle = isSel
            ? 'border-color:#6366f1; background:rgba(99,102,241,.08)'
            : 'background:rgba(18,19,24,.3)';
        const dotShadow = isSel ? 'box-shadow:0 0 0 3px rgba(99,102,241,.3)' : '';
        const statusBadge = a.status === 'active'
            ? `<span style="background:rgba(74,222,128,.1); color:#4ade80; border:1px solid rgba(74,222,128,.2)" class="text-[10px] font-bold px-1.5 py-0.5 rounded">● Active</span>`
            : `<span style="background:rgba(248,113,113,.1); color:#f87171; border:1px solid rgba(248,113,113,.2)" class="text-[10px] font-bold px-1.5 py-0.5 rounded">● Offline</span>`;
        const activeBadge = isCur
            ? `<span style="background:rgba(99,102,241,.15); color:#818cf8; border:1px solid rgba(99,102,241,.25)" class="text-[9px] font-bold px-1.5 py-0.5 rounded">AKTIF</span>`
            : '';

        return `
        <div onclick="selectAgent('${a.id}')" style="cursor:pointer">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-borderSubtle hover:border-brand/30 transition-all duration-150 select-none"
                 style="${cardStyle}">
                <div class="w-2 h-2 rounded-full flex-shrink-0 transition-all"
                     style="background:${dotBg}; ${dotShadow}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-textMain truncate">${a.name}</p>
                    <p class="text-[11px] font-mono text-textMuted">${a.ip}</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    ${statusBadge}
                    ${activeBadge}
                </div>
            </div>
        </div>`;
    }).join('');
}

function selectAgent(agentId) {
    selectedAgentId = agentId;
    renderAgentList();
}

// ============================================================
// ACTIONS — sambungkan ke route backend saat siap
// ============================================================
function doAssign() {
    if (!selectedAgentId) {
        showToast('Pilih agent terlebih dahulu.', 'warn');
        return;
    }
    const agent = ALL_AGENTS.find(a => String(a.id) === String(selectedAgentId));
    // TODO: ganti dengan form submit ke route('assignagent')
    // dengan payload: user_id = currentUserId, agent_id = selectedAgentId
    closeModal();
    showToast('Agent "' + (agent ? agent.name : selectedAgentId) + '" berhasil di-assign.', 'success');
}

function doUnassign() {
    if (!confirm('Lepas agent dari user ini?')) return;
    // TODO: ganti dengan form submit ke route('unassignagent')
    // dengan payload: user_id = currentUserId
    closeModal();
    showToast('Agent berhasil dilepas.', 'error');
}

// ============================================================
// TOAST
// ============================================================
function showToast(msg, type) {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const icons = {
        success: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        warn:    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    };
    icon.innerHTML = icons[type] || icons.success;
    document.getElementById('toast-msg').textContent = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(8px)';
    }, 3000);
}

// Tutup modal via backdrop & ESC
document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>

<script>
    lucide.createIcons();
</script>
</body>
</html>