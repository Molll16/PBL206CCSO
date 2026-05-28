<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Services\AgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function settings()
    {
        return view('Admin.profile.profileset-admin');
    }

    public function customerSettings()
    {
        return view('Customer.profile.profileset');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
        ]);

        auth()->user()->update($request->only('name', 'email', 'no_telp'));

        return back()->with('success', 'Profile berhasil diupdate');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'The current password you entered is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed' => true,
        ]);

        return back()->with('success', 'Your password has been successfully updated!')->with('password_success', true);
    }

    public function agent()
    {
        // OOP Clean: Urusan pemanggilan API diserahkan penuh ke AgentService
        try {
            $agents = collect($this->agentService->getAgents());

            return view('Admin.profile.profile-agent', [
                'agents' => $agents,
                'totalAgents' => $agents->count(),
                'assignedAgents' => Agen::count(),
                'wazuhOffline' => false,
            ]);
        } catch (\Throwable $e) {
            return view('Admin.profile.profile-agent', [
                'agents' => collect(),
                'totalAgents' => 0,
                'assignedAgents' => 0,
                'wazuhOffline' => true,
            ]);
        }
    }
}