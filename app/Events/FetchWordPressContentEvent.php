<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/*
|--------------------------------------------------------------------------
| Fetch WordPress Content Event
|--------------------------------------------------------------------------
|
| Run this event to store the WordPress Projects, Blogs and Categories
| that had been created in JSON format. You could find the JSON files
| in this path "public/db"
|
*/

class FetchWordPressContentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }
}
