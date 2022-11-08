<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\DelayStrategy;

use Manyou\PromiseHttpClient\DelayStrategyInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function round;
use function sprintf;

final class ExponentialBackOff implements DelayStrategyInterface
{
    public function __construct(private int $initialMs, private float $multiplier)
    {
        if ($initialMs < 0) {
            throw new InvalidArgumentException(sprintf(
                'Initial time of delay in milliseconds must be greater than or equal to zero: "%s" given.',
                $initialMs,
            ));
        }

        if ($multiplier < 1.0) {
            throw new InvalidArgumentException(sprintf(
                'Multiplier must be greater than or equal to one: "%s" given.',
                $multiplier,
            ));
        }
    }

    public function getDelay(int $count, ?ResponseInterface $response = null): int
    {
        return (int) round(
            $this->initialMs * $this->multiplier ** ($count - 1),
        );
    }
}
