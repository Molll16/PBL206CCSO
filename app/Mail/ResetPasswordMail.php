<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Deklarasikan variabel agar bisa dipakai di dalam content email
    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        // 2. Masukkan data token dan email saat kelas ini dipanggil
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // 3. Ubah subjek email sesuai keinginanmu di sini
            subject: 'Reset Password Akun Dashboard SIEM Anda',
        );
    }

    /**
     * Get the message content definition.
     */
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // 🛠️ DIPERBARUI: Menggunakan fungsi route() agar otomatis sinkron dengan routes/web.php
        $resetLink = route('password.reset', [
            'token' => $this->token,
            'email' => $this->email
        ]);

        return new Content(
            htmlString: "
                <div style='background-color: #121318; color: #f1f3f9; padding: 30px; font-family: sans-serif; border-radius: 12px; max-width: 500px; margin: auto;'>
                    <h2 style='color: #6366f1; margin-bottom: 10px;'>Permintaan Reset Password</h2>
                    <p style='color: #787f99; font-size: 14px; line-height: 1.5;'>Kami menerima permintaan untuk menyetel ulang password akun Dashboard One For All Anda.</p>
                    <p style='color: #787f99; font-size: 14px; line-height: 1.5;'>Silakan klik tombol di bawah ini untuk membuat password baru. Link ini bersifat rahasia.</p>
                    <br>
                    <div style='text-align: center;'>
                        <a href='{$resetLink}' style='background-color: #6366f1; color: white; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 8px; display: inline-block;'>Reset Password Baru</a>
                    </div>
                    <br><br>
                    <hr style='border: 0; border-top: 1px solid #262833;'>
                    <p style='color: #555555; font-size: 11px;'>Jika Anda tidak meminta tindakan ini, silakan abaikan saja email ini dengan aman.</p>
                </div>
            "
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}