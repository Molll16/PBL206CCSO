<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;
use Illuminate\Http\Request;
use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Models\Fitur;

class CustomerDashboardController extends Controller
{
    public function save(Request $request)
    {
        // Cek apakah user sedang update dashboard yang sudah ada atau membuat dashboard baru
        if ($request->dashboard_id) {

            // Ambil dashboard milik user yang login (keamanan: tidak bisa ambil punya orang lain)
            $dashboard = DasborKustom::where('id', $request->dashboard_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Update nama dashboard
            $dashboard->update([
                'nama_dasbor' => $request->nama_dashboard,
            ]);

            // Hapus semua widget lama (biar nanti diisi ulang)
            HasilKustom::where(
                'dasbor_kustom_id',
                $dashboard->id
            )->delete();

        } else {

            // Jika belum ada dashboard → buat dashboard baru
            $dashboard = DasborKustom::create([
                'user_id' => auth()->id(),
                'nama_dasbor' => $request->nama_dashboard,
                'status_dasbor' => 'nonaktif', // defaultnya nonaktif, nanti user bisa pilih mau pakai dashboard ini atau tidak
            ]);
        }

        // Simpan semua widget/fitur yang dipilih user ke dashboard
        foreach ($request->fitur as $item) {

            HasilKustom::create([
                'dasbor_kustom_id' => $dashboard->id, 
                'fitur_id' => $item['fitur_id'], // fitur yang dipilih user pada dashboard tertentu
                'kolom' => $item['kolom'], // posisi kolom widget/fitur pada dashboard
                'baris' => $item['baris'], // posisi baris widget/fitur pada dashboard
                'status_fitur' => 'aktif',
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }


    public function edit($id)
    {
        $dashboard = DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $fitur = Fitur::all();

        $hasil = HasilKustom::with('fitur')
            ->where('dasbor_kustom_id', $dashboard->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'fitur_id' => $item->fitur_id,
                    'name' => $item->fitur->nama_fitur,
                    'colSpan' => $item->kolom,
                    'rowSpan' => $item->baris
                ];
            });

        // Return response JSON untuk frontend (biasanya AJAX)
        return view('Customer.customize.kustom', compact(
            'dashboard',
            'fitur',
            'hasil'
        ));
    }


    // Fungsi untuk menghapus dashboard
    public function destroy($id)
    {
        // Ambil dashboard milik user yang login (keamanan: tidak bisa ambil punya orang lain)
        $dashboard = DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hapus semua widget yang terkait dengan dashboard ini
        HasilKustom::where(
            'dasbor_kustom_id',
            $dashboard->id
        )->delete();

        $dashboard->delete();

        return redirect('/choosedashboard');
    }

    // Fungsi untuk mengaktifkan/menggunakan dashboard tertentu
    public function useDashboard($id)
    {
        // Nonaktifkan semua dashboard milik user yang login
        DasborKustom::where('user_id', auth()->id())
            ->update([
                'status_dasbor' => 'nonaktif'
            ]);

        // Aktifkan dashboard yang dipilih user
        DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->update([
                'status_dasbor' => 'aktif'
            ]);

        return redirect()->route('dashboard-customer')
            ->with('success', 'Dashboard berhasil digunakan');
    }

    // Fungsi untuk menampilkan dashboard dengan data dari Wazuh API
    public function dashboard(WazuhApiService $wazuh)
    {
        // Ambil dashboard yang aktif milik user yang login (keamanan: tidak bisa ambil punya orang lain)
        $dashboard = DasborKustom::where('user_id', auth()->id())
            ->where('status_dasbor', 'aktif')
            ->first();
    
        $widgets = [];
    
        // Jika ada dashboard yang aktif, ambil semua widget/fitur yang terkait dengan dashboard tersebut
        if ($dashboard) {
            $widgets = HasilKustom::with('fitur')
                ->where('dasbor_kustom_id', $dashboard->id)
                ->get();
        }
    
        // Ambil data agent dari Wazuh API dan hitung jumlah agent online, offline, dan total

        // ambil agent milik user
        $myAgents = \DB::table('agen')
            ->where('user_id', auth()->id())
            ->pluck('id_wazuh_agen')
            ->toArray();
        
        // ambil semua agent dari wazuh
        $agents = $wazuh->agents();
        
        $agentOnline = 0;
        $agentOffline = 0;
        $agentTotal = 0;
        
        // cek apakah response dari Wazuh API mengandung data agent yang valid
        if (isset($agents['data']['affected_items'])) {
        
            $list = collect($agents['data']['affected_items']);
        
            // filter agent yang hanya milik user yang login
            $list = $list->whereIn('id', $myAgents);
        
            $agentTotal = $list->count();
        
            foreach ($list as $agent) {
                if ($agent['status'] == 'active') {
                    $agentOnline++;
                } else {
                    $agentOffline++;
                }
            }
        }
    
        // fungsi untuk menamopilkan dashboard dengan data dari Wazuh API
        return view('Customer.dashboard', compact(
            'dashboard',
            'widgets',
            'agentOnline',
            'agentOffline',
            'agentTotal'
        ));
    }
}