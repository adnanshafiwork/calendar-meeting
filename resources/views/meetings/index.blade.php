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
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                <strong>Sucess! </strong> {{ session('success') }}
                <button type="button" onclick="closeAlert('success-alert')" class="close">&times;</button>
            </div>
        @endif
        <h1 class="mb-4">Meetings</h1>
        <div class="mb-3">
            <a href="{{ route('meetings.create') }}" class="btn btn-success">Create Meeting</a>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Datetime</th>
                            <th scope="col">Attendee Email</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($meetings as $meeting)
                            <tr>
                                <td>{{ $meeting->subject }}</td>
                                <td>{{ $meeting->datetime }}</td>
                                <td>{{ $meeting->attendee_email }}</td>
                                <td>
                                    <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-info">Edit</a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $meeting->id }})">
                                        Delete
                                    </button>
                                    <form id="delete-meeting-form-{{ $meeting->id }}"
                                        action="{{ route('meetings.destroy', $meeting->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($meetings->count())
                    <div class="mt-2">
                        {{ $meetings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('external_javascript')
    <script>
        function confirmDelete(meetingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-meeting-form-' + meetingId).submit();
                }
            });
            return false;
        }
    </script>
@endsection
