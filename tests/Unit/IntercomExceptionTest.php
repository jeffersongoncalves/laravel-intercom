<?php

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Response as HttpResponse;
use JeffersonGoncalves\Intercom\Exceptions\IntercomException;

function fakeIntercomResponse(int $status, array $body): Response
{
    $psr = new GuzzleHttp\Psr7\Response($status, [], json_encode($body));

    return new HttpResponse($psr);
}

it('builds the exception message from the first error entry', function () {
    $response = fakeIntercomResponse(404, ['type' => 'error.list', 'errors' => [['code' => 'not_found', 'message' => 'Contact not found']]]);

    $exception = IntercomException::fromResponse($response);

    expect($exception->getMessage())->toBe('Contact not found')
        ->and($exception->getCode())->toBe(404)
        ->and($exception->errorBody())->toBe(['type' => 'error.list', 'errors' => [['code' => 'not_found', 'message' => 'Contact not found']]]);
});

it('falls back to a generic message when the body has no known error keys', function () {
    $response = fakeIntercomResponse(500, []);

    $exception = IntercomException::fromResponse($response);

    expect($exception->getMessage())->toBe('Intercom API error (HTTP 500).');
});
