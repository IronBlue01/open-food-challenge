<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class SlackCommandExecuted extends Notification
{
    public function __construct(
        protected string $commandName,
        protected string $status = 'success'
    ) {}

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content('🎉 Produtos importados com sucesso!!');
    }
}
