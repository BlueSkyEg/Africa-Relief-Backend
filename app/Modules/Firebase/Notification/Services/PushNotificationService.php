<?php

namespace App\Modules\Firebase\Notification\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PushNotificationService
{
    public function __construct(private readonly Messaging $messaging)
    {
    }

    public function pushPublicNotification()
    {

    }

    public function pushPrivateNotification(string $title, string $body, array $data)
    {
        $deviceTokens = [
//            'eSLBo1UVST2MRNTOVKA-6E:APA91bG9u6q3K3I4r--gD_somZsm7zX_cA_Vl73w6PSwLjGD970g_bgqW1XQQ8LGH97VEOjbawsc8vcp1JN8pr1jYSHQVbaQCsKJQc4TKdfeFiehW16XW3SZftDcBWdN1hd9OzEIo2X8',
//            'foQwc46_x-uvxWcot-bPhP:APA91bHB6H0yLYJwYxikpHCohpK9m9Sc-5EhvD5Udo1URRqNCvPaLcthKwDy1gfs3xJyVV97ELZbVS0RMhvrqKULDQHqeccviat1yuuHKFeAAxFzy-EwbHSh8XqmcW6Qx1n4CUJDnuqi',
            'eCodAYprz82Dd0FtG3R9cz:APA91bGce2CabiydPzZToc3vh8nQSlTpNtcC2hjpBbG5hcxfAyS6YtFBNZ5zyKBKvp9ir5KxITFEHT20iU5n-CWSj1GkBEFDuj13WZ2ZM8XVtqSLEq7_ZHVB2qAvTkahsVgMJhrbUXst'
        ];

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            $this->messaging->sendMulticast($message, $deviceTokens);
        } catch (MessagingException|FirebaseException $e) {
            Log::error("Error sending notifications: " . $e->getMessage());
        }
    }
}
