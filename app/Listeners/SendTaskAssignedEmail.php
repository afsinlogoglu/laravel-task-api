<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskAssignedEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskAssigned $event): void
    {
        $task = $event->task;
        $assignedUser = $event->assignedUser;

        // Send email to assigned user
        Mail::send('emails.task-assigned', [
            'task' => $task,
            'user' => $assignedUser,
        ], function ($message) use ($assignedUser, $task) {
            $message->to($assignedUser->email)
                    ->subject('New Task Assigned: ' . $task->title);
        });
    }
}
