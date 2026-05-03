<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Models\User;
use App\Services\WazuhApiService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // ================================
    // HELPER: AMBIL DATA AGENT
    // ================================
    private function getAgents(WazuhApiService $wazuh)
    {
        $response = $wazuh->agents();
        return $response['data']['affected_items'] ?? [];
    }

    // ================================
    // LIST AGENT
    // ================================
    public function agents(WazuhApiService $wazuh)
    {
        // ===== AMBIL DATA =====
        $agents = $this->getAgents($wazuh);

        // ===== RETURN VIEW =====
        return view('Admin.agents.agents-list', compact('agents'));
    }

    // ================================
    // HALAMAN ASSIGN AGENT
    // ================================
    public function assignAgentPage(WazuhApiService $wazuh)
    {
        // ===== AMBIL DATA =====
        $agents = $this->getAgents($wazuh);

        $users = User::where('role', 'customer')->get();

        // ===== RETURN VIEW =====
        return view('Admin.agents.assignagent', [
            'agents'         => $agents,
            'users'          => $users,
            'totalUsers'     => $users->count(),
            'userActive'     => $users->count(),
            'totalAgents'    => count($agents),
            'assignedAgents' => 0,
        ]);
    }

    // ================================
    // SIMPAN ASSIGN AGENT
    // ================================
    public function saveAssignAgent(Request $request, WazuhApiService $wazuh)
    {
        // ===== VALIDASI =====
        $request->validate([
            'agent_id' => ['required'],
            'user_id'  => ['required'],
        ]);

        // ===== AMBIL DATA AGENT =====
        $agents = $this->getAgents($wazuh);
        $agent = collect($agents)->firstWhere('id', $request->agent_id);
        if (!$agent) {
            return back()->with('error', 'Agent tidak ditemukan');
        }

        $namaAgen = $agent['name'] ?? 'Unknown';
        $ipAgen   = $agent['ip'] ?? '-';

        // ===== CEK DUPLIKASI =====
        $exists = Agen::where('id_wazuh_agen', $request->agent_id)->exists();

        if ($exists) {
            return back()->with('error', 'Agent sudah diassign');
        }

        // ===== SIMPAN DATA =====
        Agen::create([
            'user_id'       => $request->user_id,
            'id_wazuh_agen' => $request->agent_id,
            'nama_agen'     => $namaAgen,
            'ip_agen'       => $ipAgen,
            'status'        => 'aktif',
        ]);

        // ===== REDIRECT =====
        return redirect()
            ->route('assignagent')
            ->with('success', 'Agent berhasil di-assign');
    }
}
