<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Tags
{
    public function __construct(
        protected IntercomClient $client,
    ) {}

    public function list(): array
    {
        return $this->client->get('/tags');
    }

    public function create(string $name): array
    {
        return $this->client->post('/tags', ['name' => $name]);
    }

    public function delete(string $id): array
    {
        return $this->client->delete("/tags/{$id}");
    }
}
