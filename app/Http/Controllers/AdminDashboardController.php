<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agen;
use App\Models\User;
use App\Services\WazuhApiService;

class AdminDashboardController extends Controller
{
    // Fungsi untuk menampilkan dashboard admin
    public function index(WazuhApiService $wazuh)
    {
        // Ambil data agen dari Wazuh API
        $agents = $wazuh->agents();

        // Ambil data log/alert dari Wazuh API
        $items = $agents['data']['affected_items'] ?? [];

        $alerts = $wazuh->logs();
        $alertItems = $alerts['data']['affected_items'] ?? [];
        $active = collect($items)->where('status', 'active')->count();
        $pending = collect($items)->where('status', 'pending')->count();
        $disconnected = collect($items)->where('status', 'disconnected')->count();
        $never = collect($items)->where('status', 'never_connected')->count();

        $chartLabels = [];
        $chartData = [];
        
        // Hitung jumlah log/alert per hari selama 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
        
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('D');
        
            $total = collect($alertItems)->filter(function ($item) use ($date) {
                return isset($item['timestamp']) &&
                       str_contains($item['timestamp'], $date);
            })->count();
        
            $chartLabels[] = $label;
            $chartData[] = $total;
        }
        
        $totalAlerts = array_sum($chartData);

        return view('Admin.dashboard', compact(
            'items',
            'active',
            'pending',
            'disconnected',
            'never',
            'chartLabels',
            'chartData',
            'totalAlerts'
        ));
    }

    public function agents(WazuhApiService $wazuh)
    {
        $response = $wazuh->agents();

        $agents = $response['data']['affected_items'] ?? [];

        return view('Admin.agents-list', compact('agents'));
    }

    public function assignAgentPage(WazuhApiService $wazuh)
    {
        $response = $wazuh->agents();

        $agents = $response['data']['affected_items'] ?? [];

        $users = User::where('role', 'customer')->get();

        $totalUsers = $users->count();

        $userActive = $users->count(); // sementara

        $totalAgents = count($agents);

        $assignedAgents = 0; // nanti real setelah save assign

        return view('Admin.assignagent', compact(
            'agents',
            'users',
            'totalUsers',
            'userActive',
            'totalAgents',
            'assignedAgents'
        ));
    }

    public function saveAssignAgent(Request $request, WazuhApiService $wazuh)
    {
        $response = $wazuh->agents();
        $items = $response['data']['affected_items'] ?? [];
        $agent = collect($items)->firstWhere('id', $request->agent_id);
        $namaAgen = $agent['name'] ?? 'Unknown';
        $ipAgen   = $agent['ip'] ?? '-';

        $request->validate([
            'agent_id' => 'required',
            'user_id' => 'required'
        ]);          

        $cek = Agen::where('id_wazuh_agen', $request->agent_id)->first();

        if ($cek) {
            return back()->with('error', 'Agent sudah diassign');
        }

        Agen::create([
            'user_id'       => $request->user_id,
            'id_wazuh_agen' => $request->agent_id,
            'nama_agen'     => $namaAgen,
            'ip_agen'       => $ipAgen,
            'status'        => 'aktif'
        ]);

        return redirect()->route('assignagent')
            ->with('success', 'Agent berhasil di-assign');
    }
}