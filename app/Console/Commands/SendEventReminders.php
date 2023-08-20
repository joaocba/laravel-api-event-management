<?php

namespace App\Console\Commands;


use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees that event starts soon';

    /**
     * Execute the console command.
     */
    // This is the logic that will be executed when the command is run
     public function handle()
    {
        // Get all events that start in the next 24 hours
        $events = \App\Models\Event::with('attendees.user') // The with() method will eager load the attendees and user relationships
            ->whereBetween('start_time', [now(), now()->addDay()]) // The whereBetween() method will filter the events to only those that start in the next 24 hours
            ->get(); // The get() method will execute the query and return the results

        // Get the number of events that start in the next 24 hours
        $eventCount = $events->count();

        // Get the plural or singular label for the word 'event' based on the number of events
        $eventLabel = Str::plural('event', $eventCount);

        // Print the number of events that start in the next 24 hours with the plural or singular label
        $this->info("Found {$eventCount} {$eventLabel}.");


        // Loop through each event and print the event name and the number of attendees associated with that event
        $events->each( // The each() method will loop through each event and execute the callback function
            fn ($event) =>$event->attendees->each( // The each() method will loop through each attendee and execute the callback function
                fn ($attendee) => $attendee->user->notify(
                    new EventReminderNotification($event) // Send the notification to the user
                )
            )
        );

        // Send event reminders print to console
        $this->info('Reminder notifications sent successfully!');
    }
}
