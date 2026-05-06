<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Models\User;
use App\Services\WazuhApiService;
use App\Services\AgentService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // ================================
    // LIST AGENT
    // ================================
    public function agents(
        WazuhApiService $wazuh,
        AgentService $agentService
    )
    {
        $agents = $agentService->getAgents($wazuh);
    
        $stats = $agentService->getCustomerStats();
    
        return view('Admin.agents.agents-list', [
            'agents'               => $agents,
            'totalUsers'           => $stats['totalUsers'],
            'totalAgents'          => count($agents),
            'totalAssignedAgents'  => $stats['totalAssignedAgents'],
        ]);
    }

    // ================================
    // HALAMAN ASSIGN AGENT
    // ================================
    public function assignAgentPage(
        WazuhApiService $wazuh,
        AgentService $agentService
    )

    {
        // ===== AMBIL DATA =====
        $agents = $agentService->getAgents($wazuh);
        $stats = $agentService->getCustomerStats();

        $totalAssignedAgents = $users->sum('agents_count');

        // ===== RETURN VIEW =====
        return view('Admin.agents.assignagent', [
            'agents'               => $agents,
            'users'                => $users,
            'totalUsers'           => $users->count(),
            'totalAgents'          => count($agents),
            'totalAssignedAgents'  => $totalAssignedAgents,
        ]);
    }

    // ================================
    // SIMPAN ASSIGN AGENT
    // ================================
    public function saveAssignAgent(
        Request $request, 
        WazuhApiService $wazuh,
        AgentService $agentService
    )

    {
        // ===== VALIDASI =====
        $request->validate([
            'agent_id' => ['required'],
            'user_id'  => ['required'],
        ]);

        // ===== AMBIL DATA AGENT =====
        $agents = $agentService->getAgents($wazuh);
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
