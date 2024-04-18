<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use App\Models\Meeting;
use Illuminate\Support\Facades\Config;

class GoogleCalendarService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->setupClient();
    }

    private function setupClient()
    {
        $this->client->setAccessToken([
            'access_token'  => Config::get('google-calendar-api.access_token'),
            'refresh_token' =>  Config::get('google-calendar-api.refresh_token'),

        ]);
        if ($this->client->isAccessTokenExpired()) {
            $this->client->setClientId(config('google-calendar-api.client_id'));
            $this->client->setClientSecret(config('google-calendar-api.client_secret'));
            $this->client->setRedirectUri(config('google-calendar-api.redirect_url'));
            $this->client->setAccessType('offline');
            $newAccessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            $this->client->setAccessToken($newAccessToken);
        }
    }
    public function addEvent(Meeting $meeting)
    {
        $service = new Calendar($this->client);
        $event = new Event([
            'summary' => $meeting->subject,
            'start' => ['dateTime' => $meeting->datetime->format(\DateTime::RFC3339)],
            'end' => ['dateTime' => $meeting->getEndDateTime()->format(\DateTime::RFC3339)],
            'attendees' => [
                ['email' => $meeting->attendee_email],
                ['email' => auth()->user()->email]
            ],
        ]);
        return $service->events->insert('primary', $event);
    }
    public function updateEvent(Meeting $meeting)
    {
        $service = new Calendar($this->client);
        $event = $service->events->get('primary', $meeting->event_id);
        $event->setSummary($meeting->subject);
        $event->setStart(new \Google\Service\Calendar\EventDateTime(['dateTime' => $meeting->datetime->format(\DateTime::RFC3339)]));
        $event->setEnd(new \Google\Service\Calendar\EventDateTime(['dateTime' => $meeting->getEndDateTime()->format(\DateTime::RFC3339)]));
        $event->setAttendees([
            'attendees' => [
                ['email' => $meeting->attendee_email],
                ['email' => auth()->user()->email]
            ],
        ]);
        return $service->events->update('primary', $event->getId(), $event);
    }

    public function deleteEvent($eventId)
    {
        $service = new Calendar($this->client);
        $service->events->delete('primary', $eventId);
    }
}
