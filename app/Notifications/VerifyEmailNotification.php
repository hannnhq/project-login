<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
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
    public function via( $notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        return (new MailMessage)
            ->subject('Xác minh email của bạn')
            ->greeting('Xin chào')
            ->line('Bạn đã đăng ký tài khoản')
            ->line('Vui lòng nhấp vào xác minh email ở bên dưới')
            ->action('Xác minh email', $verificationUrl)
            ->line('Url có hiệu lực trong vòng 10 phút')
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi, chúc bạn một ngày tốt lành!');
    }

    protected function verificationUrl($notifiable) {
        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(10),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification())
            ]
        );
        dd($temporarySignedUrl);
        return $temporarySignedUrl;

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
