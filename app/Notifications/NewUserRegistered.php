<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        return (new MailMessage)
                    ->subject('Karyawan Baru Terdaftar: ' . $this->user->name)
                    ->greeting('Halo Admin,')
                    ->line('Seorang karyawan baru telah mendaftar di Sistem Absensi SPPG.')
                    ->line('Nama: ' . $this->user->name)
                    ->line('NIP: ' . $this->user->nip)
                    ->line('Telepon: ' . $this->user->phone)
                    ->action('Verifikasi Sekarang', url('/admin/user-verification'))
                    ->line('Silakan tinjau dan berikan persetujuan untuk mengaktifkan akun mereka.');
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
