<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountPendingApproval extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pendaftaran Akun Berhasil - Menunggu Persetujuan')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line('Terima kasih telah mendaftar di Sistem Absensi SPPG.')
                    ->line('Akun Anda saat ini sedang dalam status MENUNGGU PERSETUJUAN dari administrator.')
                    ->line('Anda akan menerima email pemberitahuan selanjutnya setelah akun Anda disetujui.')
                    ->line('Mohon kesabaran Anda menunggu proses verifikasi.')
                    ->action('Cek Status Pendaftaran', url('/'))
                    ->line('Terima kasih!');
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
