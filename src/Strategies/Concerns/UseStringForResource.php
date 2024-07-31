<?php
namespace Apie\CountWords\Strategies\Concerns;

trait UseStringForResource
{
    public static function countFromResource(mixed $resource, array $counts = []): array
    {
        @rewind($resource);
        return self::countFromString(stream_get_contents($resource), $counts);
    }
}
