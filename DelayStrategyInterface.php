<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface DelayStrategyInterface
{
    /**
     * @param int $count Number of retries, starts from 1
     *
     * @return int|null Time of delay in milliseconds
     */
    public function getDelay(int $count, ?ResponseInterface $response = null): ?int;
}
