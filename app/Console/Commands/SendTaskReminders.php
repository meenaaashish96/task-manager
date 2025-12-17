<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send emails for tasks due tomorrow';

    public function handle()
    {
        $tasks = Task::whereDate('due_date', Carbon::tomorrow())
                     ->where('status', '!=', 'completed')
                     ->with('user')
                     ->get();

        foreach ($tasks as $task) {
            // Queue the mail for background processing
            Mail::to($task->user->email)->queue(new TaskReminderMail($task));
        }

        $this->info('Reminders queued successfully.');
    }
}