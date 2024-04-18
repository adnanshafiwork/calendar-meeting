<?php
namespace App\Services;
use App\Models\Meeting;

class MeetingService {
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService) {
        $this->googleCalendarService = $googleCalendarService;
    }
    public function createAndSyncMeeting(array $data) {
        $meeting = Meeting::create($data);
        $event = $this->googleCalendarService->addEvent($meeting);
        $meeting->event_id = $event->getId();
        $meeting->save();
        return $meeting;
    }

    public function updateAndSyncMeeting($meeting, array $data) {

        $meeting->update($data);
        $event = $this->googleCalendarService->updateEvent($meeting);
        $meeting->event_id =  $event->getId();
        $meeting->save();
        return $meeting;
    }

    public function deleteAndSyncMeeting($meeting) {

        $this->googleCalendarService->deleteEvent($meeting->event_id);
        $meeting->delete();
        return $meeting;
    }
}
