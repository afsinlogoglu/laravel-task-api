<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskCompletedEmail implements ShouldQueue
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
    public function handle(TaskCompleted $event): void
    {
        $task = $event->task;
        $teamOwner = $task->team->owner;

        // Send email to team owner
        Mail::send('emails.task-completed', [
            'task' => $task,
            'teamOwner' => $teamOwner,
        ], function ($message) use ($teamOwner, $task) {
            $message->to($teamOwner->email)
                    ->subject('Task Completed: ' . $task->title);
        });
    }
}
