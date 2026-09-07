<?php

namespace JeffersonGoncalves\Intercom\Resources;

use JeffersonGoncalves\Intercom\IntercomClient;

class Companies
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

        return $this->client->get('/companies', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/companies/{$id}");
    }

    /** @param array<string, mixed> $attributes */
    public function create(string $companyId, array $attributes = []): array
    {
        return $this->client->post('/companies', ['company_id' => $companyId, ...$attributes]);
    }

    /** @param array<string, mixed> $attributes */
    public function update(int|string $id, array $attributes): array
    {
        return $this->client->put("/companies/{$id}", $attributes);
    }
}
