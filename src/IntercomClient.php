<?php

namespace JeffersonGoncalves\Intercom;

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Exceptions\IntercomException;

/**
 * Thin wrapper around Laravel's Http client for the Intercom REST API.
 *
 * ponytail: Http::retry() already covers transient-failure retries if ever
 * needed later — no custom retry/backoff layer built here speculatively.
 */
class IntercomClient
{
    public function __construct(
        protected string $apiKey,
        protected string $apiVersion,
    ) {}

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, $query);
    }

    /** @param array<string, mixed>|null $body */
    public function post(string $path, ?array $body = null): array
    {
        return $this->request('post', $path, $body);
    }

    /** @param array<string, mixed>|null $body */
    public function put(string $path, ?array $body = null): array
    {
        return $this->request('put', $path, $body);
    }

    /** @param array<string, mixed>|null $body */
    public function delete(string $path, ?array $body = null): array
    {
        return $this->request('delete', $path, $body);
    }

    /** @param array<string, mixed>|null $data */
    protected function request(string $method, string $path, ?array $data = null): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Intercom-Version' => $this->apiVersion,
        ])
            ->baseUrl('https://api.intercom.io')
            ->{$method}($path, $data ?? []);

        if ($response->failed()) {
            throw IntercomException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }
}
