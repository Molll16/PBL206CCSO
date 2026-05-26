<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\WazuhApiService;
use App\Models\Agen;

class ProfileController extends Controller
{
    // =========================================
    // PROFILE SETTINGS PAGE
    // =========================================
    public function settings()
    {
        return view(
            'Admin.profile.profileset-admin'
        );
    }

    // =============== //
    // UPDATE PROFILE  //
    // =============== //
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
        ]);

        // user login
        $user = auth()->user();

        // ==============================
        // UPDATE PROFILE
        // ==============================
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ]);

        // ==============================
        // CHANGE PASSWORD
        // ==============================
        if (
            $request->filled('current_password') ||
            $request->filled('new_password') ||
            $request->filled('new_password_confirmation')
        ) {

            // validasi input password
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            // cek password lama
            if (
                !Hash::check(
                    $request->current_password,
                    $user->password
                )
            ) {

                return back()->with(
                    'error',
                    'Password lama salah'
                );
            }

            // update password baru
            $user->update([
                'password' => Hash::make(
                    $request->new_password
                )
            ]);
        }

        return back()->with(
            'success',
            'Profile berhasil diupdate'
        );
    }

    // =================== //
    //  PROFILE AGENT PAGE //
    // =================== //
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

        // ========================= //
        // SHOW CHANGE PASSWORD PAGE //  
        // ========================= //
        public function showChangePw()
        {
            return view('customer.profile.changepw');
        }

        // =========================== //
        // UPDATE CHANGE PASSWORD PAGE //  
        // =========================== //
        public function updateChangePw(Request $request)
        {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|min:6|confirmed',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini salah.'
                ]);
            }

            $user->update([
                'password'         => Hash::make($request->new_password),
                'password_changed' => true,
            ]);

            return redirect()->route('customer-dashboard')
                            ->with('success', 'Password berhasil diubah!');
        }
    }
