<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Http\Requests\MeetingRequest;
use App\Services\MeetingService;

class MeetingController extends Controller
{
    protected $meetingService;

    public function __construct(MeetingService $meetingService) {
        $this->meetingService = $meetingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::where('creator_id', auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        return view('meetings.index', compact('meetings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('meetings.create_or_update');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingRequest $request)
    {
        $validated = $request->validated();
        $validated['creator_id'] = auth()->id();
        try {
            \DB::beginTransaction();
            $this->meetingService->createAndSyncMeeting($validated);
            \DB::commit();
            return redirect()->route('meetings.index')->with('success', 'Meeting created successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('meetings.index')->withErrors('Failed to create meeting: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meeting = Meeting::findOrFail($id);
        return view('meetings.show', compact('meeting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meeting = Meeting::findOrFail($id);
        return view('meetings.create_or_update', compact('meeting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingRequest $request, Meeting $meeting)
    {
        $validated = $request->validated();

        try {
            \DB::beginTransaction();
           $this->meetingService->updateAndSyncMeeting($meeting, $validated);
            \DB::commit();
            return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('meetings.index')->withErrors('Failed to update meeting: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        try {
            \DB::beginTransaction();
            $this->meetingService->deleteAndSyncMeeting($meeting);
            \DB::commit();
            return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('meetings.index')->withErrors('Failed to delete meeting: ' . $e->getMessage());
        }
    }
}
