<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Admins
{
    public function __construct(
        protected IntercomClient $client,
    ) {}

    public function list(): array
    {
        return $this->client->get('/admins');
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/admins/{$id}");
    }
}
