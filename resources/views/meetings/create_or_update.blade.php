@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger" id="error-alert">
                <strong>Error! </strong> Please fix the following errors:
                <button type="button" onclick="closeAlert('error-alert')" class="close">&times;</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
        <h1 class="mb-4">{{ isset($meeting) ? 'Update' : 'Create' }} Meeting</h1>
        <form method="POST" action="{{ isset($meeting) ? route('meetings.update', $meeting) : route('meetings.store') }}" class="needs-validation" novalidate>
            @csrf
            @if (isset($meeting))
                @method('PUT')
            @endif
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" name="subject" id="subject" value="{{ isset($meeting) ? $meeting->subject : '' }}" required>
                <div class="invalid-feedback">
                    Please provide a meeting subject.
                </div>
            </div>
            <div class="form-group">
                <label for="datetime">Date and Time:</label>
                <input type="datetime-local" class="form-control" name="datetime" id="datetime" value="{{ isset($meeting) ? date('Y-m-d\TH:i', strtotime($meeting->datetime)) : '' }}" required>
                <div class="invalid-feedback">
                    Please choose a date and time for the meeting.
                </div>
            </div>
            <div class="form-group">
                <label for="attendee1_email">Attendee  Email:</label>
                <input type="email" class="form-control" name="attendee_email" id="attendee_email" value="{{ isset($meeting) ? $meeting->attendee_email : '' }}" required>
                <div class="invalid-feedback">
                    Please provide an email address for attendee.
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($meeting) ? 'Update' : 'Submit' }}</button>
            <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
