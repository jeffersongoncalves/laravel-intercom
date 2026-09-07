<?php

namespace JeffersonGoncalves\Intercom\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\Intercom\IntercomClient;

class Contacts
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

        return $this->client->get('/contacts', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/contacts/{$id}");
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): array
    {
        if (empty($attributes['email'])) {
            throw new InvalidArgumentException('The "email" attribute is required.');
        }

        return $this->client->post('/contacts', ['role' => 'user', ...$attributes]);
    }

    /** @param array<string, mixed> $attributes */
    public function update(int|string $id, array $attributes): array
    {
        return $this->client->put("/contacts/{$id}", $attributes);
    }

    public function search(string $field, mixed $value, string $operator = '='): array
    {
        return $this->client->post('/contacts/search', [
            'query' => ['field' => $field, 'operator' => $operator, 'value' => $value],
        ]);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/contacts/{$id}");
    }

    public function tag(int|string $id, string $tagId): array
    {
        return $this->client->post("/contacts/{$id}/tags", ['id' => $tagId]);
    }

    public function untag(int|string $id, string $tagId): array
    {
        return $this->client->delete("/contacts/{$id}/tags/{$tagId}");
    }
}
