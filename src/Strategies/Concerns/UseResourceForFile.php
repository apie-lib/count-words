<?php
namespace Apie\CountWords\Strategies\Concerns;

trait UseResourceForFile
{
    public static function countFromFile(string $path, array $counts = []): array
    {
        $handle = fopen($path, 'r+');
        try {
            return self::countFromResource($handle, $counts);
        } finally {
            fclose($handle);
        }
    }
}
