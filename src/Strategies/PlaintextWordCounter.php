<?php
namespace Apie\CountWords\Strategies;

use Apie\CountWords\Strategies\Concerns\UseResourceForFile;
use Apie\CountWords\Strategies\Concerns\UseStringForResource;
use Apie\CountWords\WordCounter;

final class PlaintextWordCounter implements WordCounterInterface
{
    use UseStringForResource;
    use UseResourceForFile;
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function isSupported(?string $fileExtension, ?string $mimeType): bool
    {
        return in_array($fileExtension, ['txt', 'log']) || in_array($mimeType, ['text/plain']);
    }

    public static function countFromString(string $text, array $counts = []): array
    {
        return WordCounter::countFromString($text, $counts);
    }
}
