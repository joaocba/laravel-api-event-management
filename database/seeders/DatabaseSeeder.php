<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 1000 fake user records using the User factory
        \App\Models\User::factory(1000)->create();

        // Call the EventSeeder to populate events
        $this->call(EventSeeder::class);

        // Call the AttendeeSeeder to populate attendees
        $this->call(AttendeeSeeder::class);
    }
}
