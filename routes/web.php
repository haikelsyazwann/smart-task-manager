<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class)
         ->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('projects/{project}/board', function (Project $project) {
        abort_unless(
            auth()->user()->hasRole('admin') ||
            $project->team->members->contains(auth()->id()),
            403
        );
        return view('projects.board', compact('project'));
    })->name('projects.board');

    // Teams
    Route::resource('teams', TeamController::class)
         ->only(['index', 'store', 'update', 'destroy']);

    // My Tasks
    Route::get('my-tasks', function () {
        return view('tasks.my-tasks');
    })->name('my-tasks');

    // Activity
    Route::get('activity', function () {
        $logs = ActivityLog::with(['user', 'subject'])
            ->latest()->paginate(50);
        return view('activity.index', compact('logs'));
    })->name('activity.index');

    // Profile
    Route::view('profile', 'profile')->name('profile');

    // ── Admin ──
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::resource('users', AdminUserController::class)
         ->only(['index', 'store', 'update', 'destroy']);

    Route::get('roles', function () {
            return view('admin.roles');
        })->name('roles');
    });
});

// Logout
Route::post('logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// Dark mode toggle
Route::post('profile/dark-mode', function () {
    $user = auth()->user();
    $user->dark_mode = !$user->dark_mode;
    $user->save();
    return response()->json(['dark_mode' => $user->dark_mode]);
})->name('profile.dark-mode')->middleware('auth');

require __DIR__.'/auth.php';
