<?php
namespace Apie\CountWords\Strategies\Concerns;

trait UseTempFileForString
{
    public static function countFromString(string $text, array $counts = []): array
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'UseTempFileString');
        file_put_contents($tempFile, $text);
        try {
            return self::countFromFile($tempFile, $counts);
        } finally {
            @unlink($tempFile);
        }
    }
}
