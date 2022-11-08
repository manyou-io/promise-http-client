<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\DelayStrategy;

use Manyou\PromiseHttpClient\DelayStrategyInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function mt_rand;
use function round;
use function sprintf;

class DelayJitter implements DelayStrategyInterface
{
    public function __construct(private float $factor, private DelayStrategyInterface $strategy)
    {
        if ($factor < 0.0 || $factor > 1.0) {
            throw new InvalidArgumentException(sprintf(
                'Factor must in between of zero and one: "%s" given.',
                $factor,
            ));
        }
    }

    public function getDelay(int $count, ?ResponseInterface $response = null): ?int
    {
        if (null !== $delay = $this->strategy->getDelay($count, $response)) {
            $from  = $this->strategy->getDelay($count - 1, $response);
            $to    = $this->strategy->getDelay($count + 1, $response);
            $from  = $delay - (int) round(($delay - $from) * $this->factor);
            $to    = $delay + (int) round(($to - $delay) * $this->factor);
            $delay = mt_rand($from, $to);
        }

        return $delay;
    }
}
