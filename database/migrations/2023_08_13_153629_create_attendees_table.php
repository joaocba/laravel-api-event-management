<?php

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'attendees' table
        Schema::create('attendees', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key

            $table->foreignIdFor(User::class); // Foreign key relationship with the User model
            $table->foreignIdFor(Event::class); // Foreign key relationship with the Event model

            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
