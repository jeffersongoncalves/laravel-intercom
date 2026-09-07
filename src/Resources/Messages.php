<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Messages
{
    public function __construct(
        protected IntercomClient $client,
    ) {}

    public function create(string $body, string $adminId, string $to, string $type = 'inapp'): array
    {
        return $this->client->post('/messages', [
            'message_type' => $type,
            'body' => $body,
            'from' => ['type' => 'admin', 'id' => $adminId],
            'to' => ['type' => 'user', 'id' => $to],
        ]);
    }
}
