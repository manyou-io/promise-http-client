<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\RetryStrategy;

use Manyou\PromiseHttpClient\RetryStrategyInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function in_array;

final class DefaultRetryStrategy implements RetryStrategyInterface
{
    public function __construct(private array $statusCodes = [429, 500, 502, 503, 504])
    {
    }

    public function onResponse(ResponseInterface $response): bool
    {
        return in_array($response->getStatusCode(), $this->statusCodes, true);
    }

    public function onException(TransportExceptionInterface $exception): bool
    {
        return ! $exception instanceof InvalidArgumentException;
    }
}
