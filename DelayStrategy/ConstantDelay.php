<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\DelayStrategy;

use Manyou\PromiseHttpClient\DelayStrategyInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function sprintf;

class ConstantDelay implements DelayStrategyInterface
{
    public function __construct(private ?int $delayMs)
    {
        if ($delayMs !== null && $delayMs < 0) {
            throw new InvalidArgumentException(sprintf(
                'Time of delay in milliseconds must be greater than zero: "%s" given.',
                $delayMs,
            ));
        }
    }

    public function getDelay(int $count, ?ResponseInterface $response = null): ?int
    {
        return $this->delayMs;
    }
}
