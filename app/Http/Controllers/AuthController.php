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

// Class ini untuk: Menangani seluruh proses autentikasi user (login, logout) dan alur lupa/reset password.
// Berfungsi pada: Halaman Login, dan alur "Lupa Password" (kirim email → form reset → simpan password baru).
// Dibagian fitur: Autentikasi session, redirect berbasis role (admin/customer), serta reset password via token email.
class AuthController extends Controller
{
    // Code ini untuk: Menampilkan halaman form login.
    // Berfungsi untuk: Halaman Login (route awal sebelum user masuk ke sistem).
    public function showLoginForm()
    {
        return view('login');
    }

    // Code ini untuk: Memproses validasi kredensial dan login user, lalu redirect sesuai role.
    // Berfungsi untuk: Halaman Login, bagian aksi tombol submit form.
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

            // Hapus session agen aktif dari user sebelumnya, jaga-jaga kalau ada login langsung tanpa logout dulu
            $request->session()->forget('active_wazuh_agent_id');

            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role user yang login
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

    // Code ini untuk: Memproses logout user dan membersihkan session terkait.
    // Berfungsi untuk: Tombol "Logout" di halaman Admin maupun Customer.
    public function logout(Request $request)
    {
        // Hapus session agen aktif secara spesifik sebelum session dihancurkan
        $request->session()->forget('active_wazuh_agent_id');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    // FITUR: LOGIKA PROSES RESET PASSWORD (LUPA PASSWORD)

    // Code ini untuk: Memproses input email dari pop-up "Lupa Password" dan mengirimkan link reset ke Gmail user.
    // Berfungsi untuk: Halaman Login, bagian modal/pop-up "Forgot Password".
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Cek keberadaan email customer di database lokal
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'The email address is not registered in our system.'
            ], 404);
        }

        // Buat token pengaman unik
        $token = Str::random(60);

        // Simpan token ke tabel bawaan Laravel (password_reset_tokens), token di-hash agar aman
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        try {
            // Kirim email menggunakan mailable ResetPasswordMail
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

            return response()->json([
                'success' => true,
                'message' => 'Password reset link has been sent to your email.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email, please check .env settings: ' . $e->getMessage()
            ], 500);
        }
    }

    // Code ini untuk: Menampilkan halaman formulir pembuatan password baru saat link di email diklik.
    // Berfungsi untuk: Halaman Reset Password (dibuka dari link yang dikirim ke Gmail user).
    public function showResetForm(Request $request, $token)
    {
        return view('resetPassword', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Code ini untuk: Memvalidasi token dan menyimpan perubahan password baru ke database.
    // Berfungsi untuk: Halaman Reset Password, bagian aksi tombol submit form password baru.
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed', // Kolom input password_confirmation wajib ada di form
        ]);

        // Ambil record token yang tersimpan di database
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Validasi kecocokan token
        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->with('error', 'Token reset password not valid or has expired. Please request a new password reset link.');
        }

        // Perbarui password user di database
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token lama agar tidak bisa dipakai ulang
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Kembali ke halaman login dengan flash message sukses
        return redirect('/login')->with('success', 'password has been successfully updated. You can now log in with your new password.');
    }
}