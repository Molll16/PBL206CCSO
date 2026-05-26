<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\WazuhApiService;
use App\Models\Agen;

class ProfileController extends Controller
{
    // =========================================
    // PROFILE SETTINGS PAGE (ADMIN)
    // =========================================
    public function settings()
    {
        return view(
            'Admin.profile.profileset-admin'
        );
    }

    // =========================================
    // PROFILE SETTINGS PAGE (CUSTOMER)
    // =========================================
    public function customerSettings()
    {
        return view(
            'Customer.profile.profileset'
        );
    }

    // =========================================
    // UPDATE PROFILE
    // =========================================
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
        ]);

        // user login
        $user = auth()->user();

        // update data profile
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ]);

        return back()->with(
            'success',
            'Profile berhasil diupdate'
        );
    }

    // =========================================
    // UPDATE PASSWORD
    // =========================================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // user login
        $user = auth()->user();

        // cek password lama
        if (
            !Hash::check(
                $request->current_password,
                $user->password
            )
        ) {

            return back()->with(
                'error',
                'The current password you entered is incorrect.'
            );
        }

        // update password baru
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed' => true,
        ]);

        return back()->with('success', 'Your password has been successfully updated!')->with('password_success', true);
    }

    // =========================================
    // PROFILE AGENT PAGE
    // =========================================
    public function agent(
        WazuhApiService $wazuh
    ) {

        // ambil data agent dari wazuh
        $response = $wazuh->agents();

        // fallback kalau wazuh mati
        if (
            !empty($response['error']) ||
            empty($response['data'])
        ) {

            return view(
                'Admin.profile.profile-agent',
                [
                    'agents' => collect(),
                    'totalAgents' => 0,
                    'assignedAgents' => 0,
                    'wazuhOffline' => true,
                ]
            );
        }

        // ambil list agent
        $agents = collect(
            $response['data']['affected_items']
        );

        return view(
            'Admin.profile.profile-agent',
            [
                'agents' => $agents,

                'totalAgents' =>
                    $agents->count(),

                'assignedAgents' =>
                    Agen::count(),

                'wazuhOffline' => false,
            ]
        );
    }
}