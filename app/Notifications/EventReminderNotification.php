<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue // The ShouldQueue interface will allow the notification to be queued
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( // The constructor will receive the event that the notification is for
        public Event $event // The event that the notification is for
    )
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
        return ['mail']; // Return an array with the mail channel
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Return a mail message with a link to the event
        return (new MailMessage)
                    ->line('Reminder: You have an upcoming event!') // The first line of the email
                    ->action('View Event', route('events.show', $this->event->id)) // The action button with a link to the event
                    ->line("The event {$this->event->name} starts at {$this->event->start_time}."); // The last line of the email
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Return the event ID, name, and start time
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_start_time' => $this->event->start_time
        ];
    }
}
