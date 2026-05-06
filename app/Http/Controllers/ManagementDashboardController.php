<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DasborKustom;
use App\Models\Fitur;
use App\Models\HasilKustom;

class ManagementDashboardController extends Controller
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

        return redirect()->route('pilih-dasbor');
    }

    // Fungsi untuk mengaktifkan/menggunakan dashboard tertentu
    public function use($id)
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
}
