<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($password = null)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
                    ->subject('Selamat! Akun SPPG Anda Telah Aktif')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line('Selamat! Email dan password yang Anda daftarkan telah berhasil terverifikasi.')
                    ->line('Berikut adalah detail akun Anda:')
                    ->line('Email: ' . $notifiable->email);

        if ($this->password) {
            $mail->line('Password: ' . $this->password);
        } else {
            $mail->line('Password: (Password yang Anda buat saat pendaftaran)');
        }

        return $mail->line('Anda sudah dapat melakukan absensi menggunakan email dan password tersebut karena akun Anda telah disetujui oleh superadmin.')
                    ->action('Masuk Sekarang', url('/login'))
                    ->line('Selamat bekerja!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
