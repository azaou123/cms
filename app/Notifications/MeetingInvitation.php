<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Meeting;

class MeetingInvitation extends Notification
{
    use Queueable;

    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You are invited to a meeting: ' . $this->meeting->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You are invited to the meeting "' . $this->meeting->title . '".')
            ->line('Date: ' . $this->meeting->date->format('F d, Y'))
            ->line('Time: ' . $this->meeting->start_time . ' - ' . $this->meeting->end_time)
            ->line('Location: ' . ($this->meeting->location ?? 'N/A'))
            ->line('Type: ' . ucfirst($this->meeting->type))
            ->line('Description: ' . ($this->meeting->description ?? 'No description provided.'))
            ->action('View Meeting', url(route('meetings.show', $this->meeting->id)))
            ->line('Thank you for your participation!');
    }
}
