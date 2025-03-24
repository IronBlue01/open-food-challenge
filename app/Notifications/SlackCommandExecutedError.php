<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class SlackCommandExecutedError extends Notification
{
    public function __construct(
        protected string $commandName,
        protected string $status = 'Fail'
    ) {}

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->error()
            ->content('💥⚠️ Erro ao importar produtos!!');
    }
}
