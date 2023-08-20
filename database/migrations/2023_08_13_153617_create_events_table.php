<?php

use App\Models\User;
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
        // Create the 'events' table
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key

            $table->foreignIdFor(User::class); // Foreign key relationship with the User model
            $table->string('name'); // Event name
            $table->text('description')->nullable(); // Event description (nullable)

            $table->dateTime('start_time'); // Event start time
            $table->dateTime('end_time'); // Event end time

            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
