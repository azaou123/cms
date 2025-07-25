<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CellController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MeetingAttendanceController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ClubSettingsController;
use App\Http\Controllers\MembersController;
use Illuminate\Foundation\Auth\EmailVerificationRequest
use Illuminate\Http\Request;

// Redirect and Home
Route::get('/', [HomeController::class, 'redirectToApp']);
Route::get('/home', [HomeController::class, 'index'])->name('home');



// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Auth routes
Auth::routes();

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Members
    Route::get('/members/show_members', [ProfileController::class, 'showsmembres'])->name('shows_membres');
    Route::post('/members/show_members', [ProfileController::class, 'filtermembres'])->name('filter_membres');

    // Users
    Route::get('/users', [MembersController::class, 'index'])->name('users');
    Route::get('/users/{user}', [MembersController::class, 'show'])->name('users.show');
    Route::get('/members', [MembersController::class, 'showMembers'])->name('members.show');
    Route::get('/users/search', [MembersController::class, 'search'])->name('users.search');
});

// Cell routes
Route::resource('cells', CellController::class);
Route::get('/cells/{cell}/members', [CellController::class, 'manageMembers'])->name('cells.members');
Route::post('/cells/{cell}/members', [CellController::class, 'addMember'])->name('cells.members.add');
Route::put('/cells/{cell}/members/{user}', [CellController::class, 'updateMemberRole'])->name('cells.members.update');
Route::delete('/cells/{cell}/members/{user}', [CellController::class, 'removeMember'])->name('cells.members.remove');

// Projects
Route::middleware('auth')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/team', [ProjectController::class, 'updateTeam'])->name('projects.updateTeam');
    Route::get('/projects/search', [ProjectController::class, 'search'])->name('projects.search');
});

// Meetings
Route::middleware('auth')->group(function () {
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
    Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::get('/meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
    Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::get('/meetings/{meeting}/generate-report', [MeetingController::class, 'generateReport'])->name('meetings.generate-report');

    // Meeting attendance
    Route::post('/meetings/{meeting}/attendance', [MeetingAttendanceController::class, 'updateAttendance'])->name('meetings.update-attendance');
    Route::post('/meetings/{meeting}/manage-attendance', [MeetingAttendanceController::class, 'manageAttendance'])->name('meetings.manage-attendance');
});

// Conversations & Messages
Route::middleware('auth')->group(function () {
    Route::resource('conversations', ConversationController::class);
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/create', [ConversationController::class, 'create'])->name('conversations.create');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/start', [ConversationController::class, 'startWithUser'])->name('conversations.start-with-user');

    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}', [MessageController::class, 'pollMessages'])->name('messages.poll');
    Route::get('/conversations/{conversation}/messages/latest', [MessageController::class, 'latest'])->name('messages.latest');
    Route::match(['get', 'post'], '/conversations/{conversation}/typing', [MessageController::class, 'typing'])->name('messages.typing');
    Route::post('/messages/{message}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
});

// Events
Route::resource('events', EventController::class);
Route::post('events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');

// Event Registrations
Route::post('events/{event}/register', [EventRegistrationController::class, 'register'])->name('events.register');
Route::delete('registrations/{registration}', [EventRegistrationController::class, 'cancel'])->name('registrations.cancel');
Route::get('my-events', [EventRegistrationController::class, 'myEvents'])->name('events.my');
Route::patch('registrations/{registration}/status', [EventRegistrationController::class, 'updateStatus'])->name('registrations.update-status');

// Club Settings
Route::prefix('settings')->name('settings.')->middleware('auth')->group(function () {
    Route::get('/', [ClubSettingsController::class, 'index'])->name('index');

    // General Settings
    Route::get('/general', [ClubSettingsController::class, 'showGeneralSettings'])->name('general');
    Route::post('/general', [ClubSettingsController::class, 'updateGeneralSettings'])->name('general.update');

    // Social Media
    Route::get('/social-media', [ClubSettingsController::class, 'showSocialMediaSettings'])->name('social-media');
    Route::post('/social-media', [ClubSettingsController::class, 'updateSocialMediaSettings'])->name('social-media.update');

    // System Settings
    Route::get('/system', [ClubSettingsController::class, 'showSystemSettings'])->name('system');
    Route::post('/system', [ClubSettingsController::class, 'updateSystemSettings'])->name('system.update');

    // Appearance
    Route::get('/appearance', [ClubSettingsController::class, 'showAppearanceSettings'])->name('appearance');
    Route::post('/appearance', [ClubSettingsController::class, 'updateAppearanceSettings'])->name('appearance.update');

    // Notifications
    Route::get('/notifications', [ClubSettingsController::class, 'showNotificationSettings'])->name('notifications');
    Route::post('/notifications', [ClubSettingsController::class, 'updateNotificationSettings'])->name('notifications.update');
});