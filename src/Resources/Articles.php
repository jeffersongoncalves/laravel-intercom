<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Articles
{
    public function __construct(
        protected IntercomClient $client,
        protected int $defaultPerPage = 15,
    ) {}

    /** @param array<string, mixed> $filters */
    public function list(array $filters = []): array
    {
        $query = array_filter([
            'per_page' => $filters['per_page'] ?? $this->defaultPerPage,
            'starting_after' => $filters['starting_after'] ?? null,
        ], fn (mixed $value) => $value !== null);

        return $this->client->get('/articles', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/articles/{$id}");
    }

    public function create(string $title, int $authorId, string $state = 'draft', ?string $body = null): array
    {
        $payload = ['title' => $title, 'author_id' => $authorId, 'state' => $state];

        if ($body !== null) {
            $payload['body'] = $body;
        }

        return $this->client->post('/articles', $payload);
    }

    /** @param array<string, mixed> $attributes */
    public function update(int|string $id, array $attributes): array
    {
        return $this->client->put("/articles/{$id}", $attributes);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/articles/{$id}");
    }
}
