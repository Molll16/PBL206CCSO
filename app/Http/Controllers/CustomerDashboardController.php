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
        if ($request->dashboard_id) {

            $dashboard = DasborKustom::where('id', $request->dashboard_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $dashboard->update([
                'nama_dasbor' => $request->nama_dashboard,
            ]);

            HasilKustom::where(
                'dasbor_kustom_id',
                $dashboard->id
            )->delete();

        } else {

            $dashboard = DasborKustom::create([
                'user_id' => auth()->id(),
                'nama_dasbor' => $request->nama_dashboard,
                'status_dasbor' => 'nonaktif',
            ]);
        }


        foreach ($request->fitur as $item) {

            HasilKustom::create([
                'dasbor_kustom_id' => $dashboard->id,
                'fitur_id' => $item['fitur_id'],
                'kolom' => $item['kolom'],
                'baris' => $item['baris'],
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

        return view('Customer.kustom', compact(
            'dashboard',
            'fitur',
            'hasil'
        ));
    }


    public function destroy($id)
    {
        $dashboard = DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        HasilKustom::where(
            'dasbor_kustom_id',
            $dashboard->id
        )->delete();

        $dashboard->delete();

        return redirect('/choosedashboard');
    }

    public function useDashboard($id)
    {
        DasborKustom::where('user_id', auth()->id())
            ->update([
                'status_dasbor' => 'nonaktif'
            ]);

        DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->update([
                'status_dasbor' => 'aktif'
            ]);

        return redirect()->route('dashboard-customer')
            ->with('success', 'Dashboard berhasil digunakan');
    }

    public function dashboard(WazuhApiService $wazuh)
    {
        $dashboard = DasborKustom::where('user_id', auth()->id())
            ->where('status_dasbor', 'aktif')
            ->first();
    
        $widgets = [];
    
        if ($dashboard) {
            $widgets = HasilKustom::with('fitur')
                ->where('dasbor_kustom_id', $dashboard->id)
                ->get();
        }
    
        // Ambil data agent
        $agents = $wazuh->agents();
    
        $agentOnline  = 0;
        $agentOffline = 0;
        $agentTotal   = 0;
    
        if (isset($agents['data']['affected_items'])) {
    
            $list = $agents['data']['affected_items'];
    
            $agentTotal = count($list);
    
            foreach ($list as $agent) {
    
                if ($agent['status'] == 'active') {
                    $agentOnline++;
                } else {
                    $agentOffline++;
                }
            }
        }
    
        return view('Customer.dashboard', compact(
            'dashboard',
            'widgets',
            'agentOnline',
            'agentOffline',
            'agentTotal'
        ));
    }
}