<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Events
{
    public function __construct(
        protected IntercomClient $client,
    ) {}

    /** @param array<string, mixed>|null $metadata */
    public function create(string $name, string $userId, ?int $createdAt = null, ?array $metadata = null): array
    {
        $payload = [
            'event_name' => $name,
            'user_id' => $userId,
            'created_at' => $createdAt ?? time(),
        ];

        if ($metadata !== null) {
            $payload['metadata'] = $metadata;
        }

        return $this->client->post('/events', $payload);
    }

    public function list(string $userId, ?int $perPage = null): array
    {
        $query = array_filter([
            'type' => 'user',
            'user_id' => $userId,
            'per_page' => $perPage,
        ], fn (mixed $value) => $value !== null);

        return $this->client->get('/events', $query);
    }
}
