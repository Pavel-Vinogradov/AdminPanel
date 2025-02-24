<?php

declare(strict_types=1);

namespace App\Domain\Comments\Notifications;

use App\Domain\Comments\Entities\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment)
    {
    }

    public function via($notifiable): array
    {
        $channels = ['database', 'broadcast'];

        if (! empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Новый ответ на ваш комментарий')
            ->greeting('Привет, ' . $notifiable->name)
            ->line('Кто-то ответил на ваш комментарий:')
            ->line('"' . $this->comment->body . '"')
            ->action('Посмотреть', url('/articles/' . $this->comment->article_id))
            ->line('Спасибо за участие в обсуждении!');
    }

    // Уведомление в базу данных
    public function toDatabase($notifiable): array
    {
        return [
            'article_id' => $this->comment->article_id,
            'message' => 'Новый ответ на ваш комментарий: "' . $this->comment->body . '"',
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    // WebSockets (в реальном времени)
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'article_id' => $this->comment->article_id,
            'message' => 'Новый ответ на ваш комментарий: "' . $this->comment->body . '"',
        ]);
    }
}
