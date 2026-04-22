<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Task;
use App\Notifications\TaskDeadlineReminder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('stm:send-deadline-reminders', function () {
    $tasks = Task::query()
        ->whereBetween('deadline', [now(), now()->addDay()])
        ->where('status', '!=', Task::STATUS_DONE)
        ->with('assignees')
        ->get();

    foreach ($tasks as $task) {
        $task->assignees->each->notify(new TaskDeadlineReminder($task));
    }

    $this->info("Sent reminders for {$tasks->count()} task(s).");
})->purpose('Send email reminders for upcoming deadlines');
