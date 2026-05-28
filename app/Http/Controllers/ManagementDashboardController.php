<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DasborKustom;
use App\Models\Fitur;
use App\Models\HasilKustom;

class ManagementDashboardController extends Controller
{
    // CODE: Menyimpan susunan layout widget baru atau update layout yang lama.
    // WEB: Halaman Kustomisasi -> Tombol "Save".
    // UNTUK: Jika update, nama diperbarui dan widget lama dihapus dulu baru diisi ulang (Delete and Insert). Jika dashboard baru, sistem akan membuat record baru di database dengan status default 'nonaktif'.
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

    // CODE: Mengambil data layout dashboard yang pernah disimpan sebelumnya untuk diedit kembali.
    // WEB: Halaman List Dashboard -> Tombol "Edit" (Membuka kembali kanvas kustomisasi).
    // UNTUK: Menampilkan kembali widget-widget di posisi grid semula (colSpan dan rowSpan) berdasarkan data dari database.
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

    // CODE: Menghapus data dashboard beserta seluruh susunan widget di dalamnya.
    // WEB: Halaman List Dashboard -> Tombol "Hapus/Delete".
    // UNTUK: Membersihkan database. Record di tabel `hasil_kustoms` (widget) dihapus terlebih dahulu, baru kemudian row di `dasbor_kustoms` (parent) ikut dihapus.
    public function destroy($id)
    {
        $dashboard = DasborKustom::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        HasilKustom::where('dasbor_kustom_id', $dashboard->id)->delete();
        $dashboard->delete();

        return redirect()->route('pilih-dasbor');
    }

    // CODE: Mengubah status dashboard yang dipilih menjadi 'aktif' dan me-nonaktifkan yang lain.
    // WEB: Halaman List Dashboard -> Tombol "Gunakan/Use Dashboard".
    // UNTUK: Menentukan dashboard kustomisasi mana yang berhak tampil di halaman utama ketika Customer pertama kali login.
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