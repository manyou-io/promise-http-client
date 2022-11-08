<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\DelayStrategy;

use Manyou\PromiseHttpClient\DelayStrategyInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function sprintf;

final class IncrementalBackOff implements DelayStrategyInterface
{
    public function __construct(private int $initialMs)
    {
        if ($initialMs < 0) {
            throw new InvalidArgumentException(sprintf(
                'Initial time of delay in milliseconds must be greater than or equal to zero: "%s" given.',
                $initialMs,
            ));
        }

        $this->initialMs = $initialMs;
    }

    public function getDelay(int $count, ?ResponseInterface $response = null): int
    {
        return $this->initialMs * $count;
    }
}
