<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Services\AgentService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    protected $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function agents()
    {
        $agents = $this->agentService->getAgents();
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.agents.agents-list', [
            'agents' => $agents,
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    public function assignAgentPage()
    {
        $agents = $this->agentService->getAvailableAgents();
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.agents.assignagent', [
            'agents' => $agents,
            'users' => $stats['users'],
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    public function saveAssignAgent(Request $request)
    {
        $request->validate([
            'agent_id' => ['required'],
            'user_id' => ['required'],
        ]);

        $agent = collect($this->agentService->getAgents())->firstWhere('id', $request->agent_id);

        if (!$agent) {
            return back()->with('error', 'Agent tidak ditemukan');
        }

        if (Agen::where('id_wazuh_agen', $request->agent_id)->exists()) {
            return back()->with('error', 'Agent sudah diassign');
        }

        Agen::create([
            'user_id' => $request->user_id,
            'id_wazuh_agen' => $request->agent_id,
            'nama_agen' => $agent['name'] ?? 'Unknown',
            'ip_agen' => $agent['ip'] ?? '-',
            'status' => 'aktif',
        ]);

        return redirect()->route('assignagent')->with('success', 'Agent berhasil di-assign');
    }
}