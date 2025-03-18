<?php
namespace Apie\CountWords\Strategies;

use Apie\CountWords\Strategies\Concerns\UseResourceForFile;
use Apie\CountWords\Strategies\Concerns\UseStringForResource;
use Apie\CountWords\WordCounter;

final class XmlWordCounter implements WordCounterInterface
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
        return in_array($fileExtension, ['xml']) || in_array($mimeType, ['application/xml', 'text/xml']);
    }

    public static function countFromString(string $text, array $counts = []): array
    {
        $text = strip_tags(str_replace(['<', '>'], [' <', '> '], $text));

        return WordCounter::countFromString($text, $counts);
    }
}
