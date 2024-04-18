<?php

use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/google-calendar', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google.calendar.auth');
Route::get('/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google.calendar.callback');


Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::resource('meetings', MeetingController::class);
});

Route::get('/test-credentials', function () {
    $path = config('services.google.client_secret_json');
    try {
        $contents = json_decode(file_get_contents($path), true);
        return response()->json(['path' => $path, 'exists' => file_exists($path), 'contents' => $contents]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
