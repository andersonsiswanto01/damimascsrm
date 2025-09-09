<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected string $message;
    protected ?string $filePath;

   public function __construct(string $message, ?string $filePath = null)
    {
        $this->message = $message;
        $this->filePath = $filePath;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

     public function toDatabase($notifiable): array
    {
        return [
            'message' => $this->message,
            'file' => $this->filePath,
            'timestamp' => now(),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       return [
            'title' => 'New Order Received',
            'body' => 'A new order has been placed.',
            'url' => route('filament.admin.resources.orders.index'),
        ];
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
