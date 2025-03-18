<?php
namespace Apie\CountWords\Strategies;

use Apie\CountWords\Strategies\Concerns\UseResourceForFile;
use Apie\CountWords\Strategies\Concerns\UseStringForResource;
use Apie\CountWords\WordCounter;

final class JsonWordCounter implements WordCounterInterface
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
        return in_array($fileExtension, ['json', 'jsonld']) || in_array($mimeType, ['application/json', 'application/ld+json']);
    }

    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    private static function count(mixed $decoded, array $counts = []): array
    {
        if ($decoded === null) {
            return $counts;
        }
        switch (get_debug_type($decoded)) {
            case 'string':
                return WordCounter::countFromString($decoded, $counts);
            case 'int':
            case 'float':
                return WordCounter::countFromString((string) $decoded, $counts);
            default:
                $checkKeys = substr(json_encode($decoded), 0, 1) === '{';
                foreach ($decoded as $key => $value) {
                    if ($checkKeys) {
                        $counts = WordCounter::countFromString((string) $key, $counts);
                    }
                    $counts = self::count($value, $counts);
                }
        }
        return $counts;
    }

    public static function countFromString(string $text, array $counts = []): array
    {
        $decoded = json_decode($text, true);
        return self::count($decoded, $counts);
    }
}
