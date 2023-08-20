<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Use the CanLoadRelationships trait to load relationships
    use CanLoadRelationships;

    // Load the user relationship for all methods
    private array $relations = ['user', 'attendees', 'attendees.user'];

    // __construct method to authorize all methods except index and show
    public function __construct()
    {
        // Authorize all methods except index and show
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        // Rate limit the store, update and destroy methods with api throttle settings (defined in Providers/RouteServiceProvider.php)
        $this->middleware('throttle:api')->only(['store', 'update', 'destroy']);

        // This is the same as the above but with policies
        $this->authorizeResource(Event::class, 'event');

        // Reference Authorizantion methods: https://laravel.com/docs/8.x/authorization#via-controller-helpers
        // index: viewAny
        // show: view
        // create: create
        // store: create
        // edit: update
        // update: update
        // destroy: delete

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all events
        //return \App\Models\Event::all();

        // Return all users
        //return \App\Models\User::all();

        // Return only defined fields from EventResource (API)
        //return EventResource::collection(Event::all()); // Return all events

        // Initialize a query builder for the Event model
        $query = $this->loadRelationships(Event::query());

        // Return a collection of Event resources paginated and sorted by latest
        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data and create a new event
        $event = Event::create([
            ... $request->validate([
                'name' => 'required|string|max:255',          // Validate and assign the event name (maximum 255 characters)
                'description' => 'nullable|string',           // Validate and assign the event description (nullable)
                'start_time' => 'required|date',              // Validate and assign the event start time (required date)
                'end_time' => 'required|date|after:start_time' // Validate and assign the event end time (required date after start_time)
            ]),

            // Assign the authenticated user ID to the event
            'user_id' => $request->user()->id
        ]);

        // Return only defined fields from EventResource (API)
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Return the event
        //return $event;

        // Return only defined fields from EventResource (API)
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Deny access if the authenticated user is not the event owner based on gate set at AuthServiceProvider.php
        /*if (Gate::denies('update-event', $event)) {
            abort(403, 'You are not authorized to update this event'); // Return a 403 Forbidden response
        } */

        // Authorize the authenticated user to update the event, alternatively use the Gate::denies method above
        //$this->authorize('update-event', $event);

        // Update the event with the validated request data
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',          // Validate and update the event name if provided (maximum 255 characters)
                'description' => 'nullable|string',           // Validate and update the event description if provided (nullable)
                'start_time' => 'sometimes|date',              // Validate and update the event start time if provided (date)
                'end_time' => 'sometimes|date|after:start_time' // Validate and update the event end time if provided (date after start_time)
            ])
        );

        //return $event; // Return the updated event

        // Return only defined fields from EventResource (API)
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Delete the specified event
        $event->delete();

        // Best practice
        return response(status: 204); // Return code 204 No Content

        // Delete with response message Success
/*         return response()->json([
            'message' => 'Event deleted successfully'
        ]); // Return a JSON response confirming the deletion */
    }
}
