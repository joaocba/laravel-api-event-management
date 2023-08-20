<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing users from the User model
        $users = User::all();

        // Loop to create and associate 200 events with random users
        for ($i = 0; $i < 200; $i++) {
            // Get a random user from the collection of users
            $user = $users->random();

            // Create a new event using the Event factory and associate it with the selected user
            \App\Models\Event::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
