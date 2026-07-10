<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // Coba login dengan kredensial yang diberikan
        if (Auth::attempt($credentials)) {

            // AMAN: Hapus session active agent dari user sebelumnya jika login langsung tanpa logout
            $request->session()->forget('active_wazuh_agent_id');

            $request->session()->regenerate();

            // Redirect ke dashboard sesuai dengan role user yang login
            if (Auth::user()->role == 'admin') {
                return redirect()->route('dashboard-admin');
            }

            return redirect()->route('dashboard-customer');
        }

        return back()->with(
            'error',
            'Login gagal, username / password salah.'
        );
    }

    // Menangani proses logout
    public function logout(Request $request)
    {
        // AMAN: Hapus session active agent secara spesifik sebelum session dihancurkan
        $request->session()->forget('active_wazuh_agent_id');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // =========================================================================
    // 🛠️ FITUR BARU: LOGIKA PROSES RESET PASSWORD (LUPA PASSWORD)
    // =========================================================================

    /**
     * 1. Memproses input email dari pop-up dan mengirimkan link rahasia ke Gmail
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Cek keberadaan email kustomer di database lokal
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak terdaftar di sistem kami.'
            ], 404);
        }

        // Buat token pengaman unik
        $token = Str::random(60);

        // Simpan token ke dalam tabel internal bawaan Laravel (password_reset_tokens)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token), // Di-hash agar aman
                'created_at' => now()
            ]
        );

        try {
            // Kirim Gmail menggunakan mailable ResetPasswordMail
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

            return response()->json([
                'success' => true,
                'message' => 'Link reset password berhasil dikirim ke Gmail Anda.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email, silakan cek setting .env: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 2. Menampilkan halaman formulir pembuatan password baru (saat link di Gmail diklik)
     */
    public function showResetForm(Request $request, $token)
    {
        // Kita arahkan ke dalam folder bernama 'auth' dan file bernama 'reset-password-form.blade.php'
        return view('resetPassword', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * 3. Menyimpan data perubahan password baru ke database
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed', // Kolom input password_confirmation wajib ada di form nanti
        ]);

        // Ambil record token yang ada di database
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Validasi kecocokan tokennya
        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
        }

        // Perbarui password user ke database
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token yang lama agar tidak bisa dipakai ulang
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Lempar kembali ke halaman login utama dengan flash message sukses
        return redirect('/login')->with('success', 'Password berhasil diperbarui! Silakan login kembali.');
    }
}