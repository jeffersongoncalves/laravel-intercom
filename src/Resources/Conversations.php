<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Conversations
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

        return $this->client->get('/conversations', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/conversations/{$id}");
    }

    public function search(string $field, mixed $value, string $operator = '='): array
    {
        return $this->client->post('/conversations/search', [
            'query' => ['field' => $field, 'operator' => $operator, 'value' => $value],
        ]);
    }

    public function reply(int|string $id, string $body, string $adminId): array
    {
        return $this->client->post("/conversations/{$id}/reply", [
            'message_type' => 'comment',
            'type' => 'admin',
            'admin_id' => $adminId,
            'body' => $body,
        ]);
    }

    public function close(int|string $id, string $adminId, string $body = ''): array
    {
        return $this->client->post("/conversations/{$id}/parts", [
            'message_type' => 'close',
            'type' => 'admin',
            'admin_id' => $adminId,
            'body' => $body,
        ]);
    }
}
