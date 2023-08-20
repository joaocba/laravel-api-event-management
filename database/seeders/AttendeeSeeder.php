<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing users and events from their respective models
        $users = \App\Models\User::all();
        $events = \App\Models\Event::all();

        // Loop through each user and assign them random events to attend
        foreach ($users as $user) {
            // Select a random number of events (between 1 and 3) for the user to attend
            $eventsToAttend = $events->random(rand(1, 3));

            // Loop through the selected events and create Attendee entries
            foreach ($eventsToAttend as $event) {
                \App\Models\Attendee::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
            }
        }
    }
}
