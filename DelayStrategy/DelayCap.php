<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\DelayStrategy;

use Manyou\PromiseHttpClient\DelayStrategyInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function sprintf;

class DelayCap implements DelayStrategyInterface
{
    public function __construct(
        private int $maxMs,
        private DelayStrategyInterface $strategy,
        private bool $fallthrough = false,
    ) {
        if ($maxMs < 0) {
            throw new InvalidArgumentException(sprintf(
                'Maximum time of delay in milliseconds must be greater than zero: "%s" given.',
                $maxMs,
            ));
        }
    }

    public function getDelay(int $count, ?ResponseInterface $response = null): ?int
    {
        $delay = $this->strategy->getDelay($count, $response);

        if ($delay === null) {
            return null;
        }

        if ($delay > $this->maxMs) {
            return $this->fallthrough ? null : $this->maxMs;
        }

        return $delay;
    }
}
