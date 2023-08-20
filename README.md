# Event Management - Laravel RESTful API App

This is a simple event management application built using Laravel 10, featuring RESTful APIs. The app allows you to retrieve and manage events, their attendees, and the users who created the events. It also includes optional relation loading, authentication, route protection, gates, and policies. Additionally, the application offers an Event Reminder through a custom command, task scheduling, email notifications, and queues. To prevent request exhaustion, API throttling has been applied.

## Features 🚀

- User Authentication and Authorization
- Event Management
  - Retrieve all events
  - Retrieve a single event
  - Retrieve a single event with user data relation
  - Retrieve a single event with attendee data relation
  - Retrieve a single event with attendee data relation and the user who created the attendee
  - Create an event
  - Update an event
  - Delete an event
- Attendee Management
  - Retrieve all attendees of an event
  - Retrieve a single attendee of an event
  - Retrieve a single attendee of an event with user data relation
  - Attend an event
  - Unattend an event
- Event Reminder
  - Set event reminders through a custom command
  - Task scheduling for reminders
  - Email notifications and queues for reminders
- API Throttling
- Easy-to-use API endpoints and data retrieval

## Usage 🛠️

1. Clone the repository.
2. Install dependencies: `composer install`.
3. Configure your `.env` file with your database settings.
4. Run migrations: `php artisan migrate`.
5. Start the development server: `php artisan serve`.
6. Test the APIs using tools like Postman.
7. Experience email notifications with Mailpit using the "Send Event Reminders" custom command (requires `php artisan queue:work`): `php artisan app:send-event-reminders`.

## API List 📊

- Base URL: `127.0.0.1:8000/api`

### Auth:
- Login (POST): `/login`
  - JSON body: 
  ```json
  {
      "email": "gislason.lucas@example.org",
      "password": "password"
  }
  ```
  - Default user password is "password"
- Logout (POST): `/logout`

### Events:
- All Events (GET): `/events`
- Single Event (GET): `/events/{event id}`
- Single Event with User Data Relation (GET): `/events/{event id}?include=user`
- Single Event with Attendees Data Relation (GET): `/events/{event id}?include=attendees`
- Single Event with Attendees Data Relation and User That Created the Attendee (GET): `/events/{event id}?include=attendees.user`
- Create Event (POST): `/events`
  - JSON body:
  ```json
  {
      "name": "First event",
      "start_time": "2023-09-01 15:00:00",
      "end_time": "2023-10-01 15:00:00"
  }
  ```
- Update Event (PUT): `/events/{event id}`
  - JSON body:
  ```json
  {
      "name": "Modified event name"
  }
  ```
- Delete Event (DELETE): `/events/{event id}`

### Attendees:
- All Attendees of an Event (GET): `/events/{event id}/attendees`
- Single Attendee of an Event (GET): `/events/{event id}/attendees/{attendee id}`
- Single Attendee of an Event with User Data Relation (GET): `/events/{event id}/attendees/{attendee id}?include=user`
- Attend an Event (POST): `/events/{event id}/attendees`
  - Authentication required
- Unattend an Event (DELETE): `/events/{event id}/attendees/{attendee id}`
  - Authentication required

## Contributing 🤝

Contributions are welcome! Feel free to create pull requests for enhancements or fixes.

This refined version provides more detailed and organized information about the application's features and the API endpoints. It also offers clearer instructions for usage and highlights the authentication requirements for certain actions.