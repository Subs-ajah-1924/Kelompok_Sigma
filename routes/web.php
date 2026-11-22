<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskReadController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.status');
    Route::post('tasks/{task}/read', TaskReadController::class)->name('tasks.markAsRead');

    Route::resource('courses', CourseController::class)->except(['show']);

    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    Route::resource('agendas', AgendaController::class)
        ->only(['store', 'update', 'destroy']);

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{group}/done', [NotificationController::class, 'markGroupDone'])
        ->name('notifications.markGroupDone');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/theme', [ThemeController::class, 'update'])->name('profile.theme');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
