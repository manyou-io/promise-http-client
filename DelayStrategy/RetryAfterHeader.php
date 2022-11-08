<?php

declare(strict_types=1);

namespace Manyou\PromiseHttpClient\DelayStrategy;

use DateTimeImmutable;
use DateTimeZone;
use Manyou\PromiseHttpClient\DelayStrategyInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function time;
use function trim;

final class RetryAfterHeader implements DelayStrategyInterface
{
    public function __construct(private ?int $mockTimestamp = null)
    {
    }

    public function getDelay(int $count, ?ResponseInterface $response = null): ?int
    {
        if ($response !== null) {
            $headers = $response->getHeaders(false);

            if (isset($headers['retry-after'][0])) {
                $seconds = $this->parseToSeconds($headers['retry-after'][0]);

                if ($seconds !== null) {
                    return $seconds * 1000;
                }
            }
        }

        return null;
    }

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Retry-After */
    private function parseToSeconds(string $value): ?int
    {
        $seconds = (int) $value = trim($value);

        if ($seconds >= 0) {
            if ($value === (string) $seconds) {
                return $seconds;
            }

            $value = DateTimeImmutable::createFromFormat('D, d M Y H:i:s \G\M\T', $value, new DateTimeZone('UTC'));

            if ($value) {
                return $value->getTimestamp() - ($this->mockTimestamp ?? time());
            }
        }

        return null;
    }
}
