<?php
namespace Apie\CountWords\Strategies\Concerns;

trait UseStringForResource
{
    public static function countFromResource(mixed $resource, array $counts = []): array
    {
        return self::countFromString(stream_get_contents($resource), $counts);
    }
}
