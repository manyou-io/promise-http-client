<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient;

use GuzzleHttp\Promise\PromiseInterface;

interface PromiseHttpClientInterface
{
    /**
     * Sends a request and returns a promise that resolves to a
     * ResponseInterface or fails with a TransportExceptionInterface.
     *
     * These additional options are supported but will not be passed
     * to the underlying HttpClient.
     *  - delay (int) - the time that the request should be delayed in milliseconds
     *
     * @see \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    public function request(string $method, string $url, array $options = []): PromiseInterface;
}
