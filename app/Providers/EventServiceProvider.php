<?php

namespace App\Providers;

use App\Events\TaskAssigned;
use App\Events\TaskCompleted;
use App\Listeners\SendTaskAssignedEmail;
use App\Listeners\SendTaskCompletedEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskAssigned::class => [
            SendTaskAssignedEmail::class,
        ],
        TaskCompleted::class => [
            SendTaskCompletedEmail::class,
        ],
    ];
} 