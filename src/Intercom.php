<?php

namespace JeffersonGoncalves\Intercom;

use JeffersonGoncalves\Intercom\Resources\Admins;
use JeffersonGoncalves\Intercom\Resources\Articles;
use JeffersonGoncalves\Intercom\Resources\Companies;
use JeffersonGoncalves\Intercom\Resources\Contacts;
use JeffersonGoncalves\Intercom\Resources\Conversations;
use JeffersonGoncalves\Intercom\Resources\Events;
use JeffersonGoncalves\Intercom\Resources\Messages;
use JeffersonGoncalves\Intercom\Resources\Tags;

/**
 * Entry point exposing one resource per Intercom REST API group.
 */
class Intercom
{
    protected IntercomClient $client;

    public function __construct(string $apiKey, string $apiVersion = '2.11', protected int $defaultPerPage = 15)
    {
        $this->client = new IntercomClient($apiKey, $apiVersion);
    }

    public function contacts(): Contacts
    {
        return new Contacts($this->client, $this->defaultPerPage);
    }

    public function conversations(): Conversations
    {
        return new Conversations($this->client, $this->defaultPerPage);
    }

    public function messages(): Messages
    {
        return new Messages($this->client);
    }

    public function companies(): Companies
    {
        return new Companies($this->client, $this->defaultPerPage);
    }

    public function tags(): Tags
    {
        return new Tags($this->client);
    }

    public function articles(): Articles
    {
        return new Articles($this->client, $this->defaultPerPage);
    }

    public function admins(): Admins
    {
        return new Admins($this->client);
    }

    public function events(): Events
    {
        return new Events($this->client);
    }
}
