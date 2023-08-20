<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Set the authorization gate for updating an event, the user must be the owner of the event
        Gate::define('update-event', function ($user, Event $event) {
            // Check if the user is the owner of the event
            return $user->id === $event->user_id;
        });

        // Set the authorization gate for deleting an attendee, the user must be the owner of the event, or the attendee
        Gate::define('delete-attendee', function ($user, Event $event, Attendee $attendee) {
            // Check if the user is the owner of the event, or the attendee
            return $user->id === $event->user_id || $user->id === $attendee->user_id;
        });
    }
}
