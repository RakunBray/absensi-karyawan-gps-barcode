<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejected extends Notification
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
                    ->subject('Pemberitahuan Status Pendaftaran Akun')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line('Kami informasikan bahwa pendaftaran akun Anda saat ini BELUM DAPAT DISETUJUI.')
                    ->line('Mohon maaf, profil Anda belum memenuhi kriteria atau data yang diberikan tidak valid.')
                    ->action('Cek Status', url('/'))
                    ->line('Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator kami.');
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
