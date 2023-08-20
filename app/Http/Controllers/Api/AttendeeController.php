<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{

    // Use the CanLoadRelationships trait to load relationships
    use CanLoadRelationships;

    // Load the user relationship for all methods
    private array $relations = ['user'];

    // __construct method to authorize all methods except index and show
    public function __construct()
    {
        // Authorize all methods except index and show
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);

        // Rate limit the store and destroy methods with api throttle settings (defined in Providers/RouteServiceProvider.php)
        $this->middleware('throttle:api')->only(['store', 'destroy']);

        // This is the same as the above but with policies
        $this->authorizeResource(Attendee::class, 'attendee');

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
    public function index(Event $event)
    {
        // Load the attendees relationship and sort them by the latest entry
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        // Return a collection of Attendee resources paginated
        // This collection will include the attendees loaded along with their relationships
        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        // Create a new attendee for the provided event and user
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => $request->user()->id, // Get the authenticated user id
            ])
        );

        // Return a AttendeeResource for the newly created attendee
        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        // Return a AttendeeResource for the specified attendee
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        // Delete the specified attendee
        $attendee->delete();

        return response(status: 204);
    }
}
