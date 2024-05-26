<?php

namespace App\Listeners;

use App\Events\Project\ProjectProgress;
use App\Modules\Firebase\Notification\Services\PushNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProjectNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(private readonly PushNotificationService $pushNotificationService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ProjectProgress $event): void
    {
        $this->pushNotificationService->pushPrivateNotification('Title', 'Body', []);
    }
}
