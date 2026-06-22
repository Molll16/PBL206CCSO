<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DasborKustom;
use App\Models\Fitur;
use App\Models\HasilKustom;

class ManagementDashboardController extends Controller
{
    // Code ini untuk: Menyimpan susunan layout kustomisasi widget baru atau memperbarui data lama.
    // Berfungsi untuk: Aksi tombol "Save" pada kanvas kustomisasi dashboard Customer.
    public function save(Request $request)
    {
        if ($request->dashboard_id) {
            $dashboard = DasborKustom::where('id', $request->dashboard_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $dashboard->update([
                'nama_dasbor' => $request->nama_dashboard,
            ]);

            HasilKustom::where('dasbor_kustom_id', $dashboard->id)->delete();
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

        return response()->json(['success' => true]);
    }

    // Code ini untuk: Mengambil konfigurasi grid layout dashboard yang tersimpan untuk dimuat ulang.
    // Berfungsi untuk: Menampilkan data pada halaman editor kustomisasi di panel Customer.
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

        return view('Customer.customize.kustom', compact('dashboard', 'fitur', 'hasil'));
    }

    // Code ini untuk: Menghapus satu template dashboard beserta seluruh relasi susunan widget di dalamnya.
    // Berfungsi untuk: Tombol aksi "Hapus/Delete Dashboard" pada panel list Customer.
    public function destroy($id)
    {
        $dashboard = DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        HasilKustom::where('dasbor_kustom_id', $dashboard->id)->delete();
        $dashboard->delete();

        return redirect()->route('pilih-dasbor');
    }

    // Code ini untuk: Mengaktifkan satu template dashboard terpilih dan me-nonaktifkan yang lainnya.
    // Berfungsi untuk: Tombol aksi "Gunakan/Use Dashboard" pada menu pilihan dashboard Customer.
    public function use($id)
    {
        DasborKustom::where('user_id', auth()->id())
            ->update(['status_dasbor' => 'nonaktif']);

        DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['status_dasbor' => 'aktif']);

        return redirect()->route('dashboard-customer')
            ->with('success', 'Dashboard berhasil digunakan');
    }
}