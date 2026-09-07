<div class="filament-hidden">

![Laravel Intercom](https://raw.githubusercontent.com/jeffersongoncalves/laravel-intercom/main/art/jeffersongoncalves-laravel-intercom.png)

</div>

# Laravel Intercom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-intercom.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-intercom)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-intercom/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-intercom/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-intercom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-intercom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-intercom.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-intercom)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-intercom.svg?style=flat-square)](LICENSE.md)

A PHP/Laravel client for the [Intercom](https://www.intercom.com/) REST API. Covers contacts, conversations, messages, companies, tags, articles, admins and events through a simple, typed API built on Laravel's `Http` client.

## Features

- Contacts: list, get, create, update, search, delete, tag/untag
- Conversations: list, get, search, reply, close
- Messages: create (in-app or email, from an admin to a user)
- Companies: list, get, create, update
- Tags: list, create, delete
- Articles: list, get, create, update, delete
- Admins: list, get
- Events: create, list (per user)
- Throws `IntercomException` (with the original API error body) on any non-2xx response
- Throws `InvalidArgumentException` before hitting the API when a required field is missing

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-intercom
```

Publish the config file:

```bash
php artisan vendor:publish --tag=intercom-config
```

Set your Intercom credentials in `.env`:

```env
INTERCOM_API_KEY=your-access-token
```

Find your access token under **Settings > Developers** in your Intercom workspace.

## Configuration

```php
// config/intercom.php
return [
    'api_key' => env('INTERCOM_API_KEY', ''),
    'api_version' => env('INTERCOM_API_VERSION', '2.11'),
    'default_per_page' => env('INTERCOM_DEFAULT_PER_PAGE', 15),
];
```

## Usage

The package is resolved via the `Intercom` facade or by injecting `JeffersonGoncalves\Intercom\Intercom`. Each resource is exposed as a method returning a dedicated resource class.

### Contacts

```php
use JeffersonGoncalves\Intercom\Facades\Intercom;

// List (cursor-based pagination via per_page / starting_after)
$contacts = Intercom::contacts()->list(['starting_after' => $cursor]);

$contact = Intercom::contacts()->get('507f191e810c19729de860ea');

$contact = Intercom::contacts()->create([
    'email' => 'jane@example.com',
    'name' => 'Jane Doe',
]);

Intercom::contacts()->update($contact['id'], ['name' => 'Jane D.']);

Intercom::contacts()->search('email', 'jane@example.com');

Intercom::contacts()->tag($contact['id'], $tagId);
Intercom::contacts()->untag($contact['id'], $tagId);

Intercom::contacts()->delete($contact['id']);
```

### Conversations

```php
$conversations = Intercom::conversations()->list();

$conversation = Intercom::conversations()->get(1);

Intercom::conversations()->search('state', 'open');

Intercom::conversations()->reply($conversation['id'], 'Thanks for reaching out!', $adminId);

Intercom::conversations()->close($conversation['id'], $adminId, 'Resolved.');
```

### Messages

```php
Intercom::messages()->create(
    body: 'Welcome aboard!',
    adminId: $adminId,
    to: $userId,
);
```

### Companies

```php
$companies = Intercom::companies()->list();

$company = Intercom::companies()->create('company-42', ['name' => 'Acme Inc']);

Intercom::companies()->update($company['id'], ['name' => 'Acme Inc.']);
```

### Tags

```php
$tags = Intercom::tags()->list();

$tag = Intercom::tags()->create('vip');

Intercom::tags()->delete($tag['id']);
```

### Articles

```php
$articles = Intercom::articles()->list();

$article = Intercom::articles()->create(
    title: 'How to reset your password',
    authorId: $adminId,
    state: 'published',
    body: '<p>Steps to reset your password...</p>',
);

Intercom::articles()->update($article['id'], ['title' => 'How to reset your password (updated)']);

Intercom::articles()->delete($article['id']);
```

### Admins and Events

```php
Intercom::admins()->list();
Intercom::admins()->get($adminId);

Intercom::events()->create('invited-friend', $userId, metadata: ['invitee' => 'jane@example.com']);
Intercom::events()->list($userId);
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\Intercom\Exceptions\IntercomException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\Intercom\Exceptions\IntercomException;

try {
    Intercom::contacts()->get('unknown-id');
} catch (IntercomException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

Missing required fields (e.g. `email` on `contacts()->create()`) throw `InvalidArgumentException` before any HTTP call is made.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
