<?php
namespace Apie\CountWords;

use Apie\CountWords\Strategies\HtmlWordCounter;
use Apie\CountWords\Strategies\JsonWordCounter;
use Apie\CountWords\Strategies\OfficeDocumentWordCounter;
use Apie\CountWords\Strategies\PdfFileWordCounter;
use Apie\CountWords\Strategies\PlaintextWordCounter;
use Apie\CountWords\Strategies\XmlWordCounter;
use Apie\CountWords\Strategies\ZipArchiveWordCounter;

class WordCounter
{
    private const FILE_STRATEGIES = [
        JsonWordCounter::class,
        OfficeDocumentWordCounter::class,
        PdfFileWordCounter::class,
        HtmlWordCounter::class,
        XmlWordCounter::class,
        PlaintextWordCounter::class,
        ZipArchiveWordCounter::class,
    ];

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    public static function countFromFile(string $path, array $counts = [], ?string $mimeType = null): array
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        foreach (self::FILE_STRATEGIES as $fileStrategyClass) {
            if ($fileStrategyClass::isSupported($extension, $mimeType)) {
                return $fileStrategyClass::countFromFile($path, $counts);
            }
        }
        return $counts;
    }
    
    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    public static function countFromString(string $text, array $counts = [], ?string $mimeType = null, ?string $extension = null): array
    {
        $originalText = mb_strtolower(trim($text));
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s.]/u', '', $text);
        $wordsAndNumbers = preg_split('/[\s]+/', $text);

        foreach (self::FILE_STRATEGIES as $fileStrategyClass) {
            if ($fileStrategyClass::isSupported($extension, $mimeType)) {
                return $fileStrategyClass::countFromString($text, $counts);
            }
        }
        if ($mimeType !== null || $extension !== null) {
            return $counts;
        }
            
        // Iterate through each word/number and count its frequency
        foreach ($wordsAndNumbers as $item) {
            // we could not remove '.' beforehand all the time as a floating point would lose the '.' as well.
            $item = rtrim($item, '.');
            if (empty($item)) {
                continue;
            }
                
            if (array_key_exists($item, $counts)) {
                $counts[$item]++;
            } else {
                $counts[$item] = 1;
            }
        }

        if (empty($counts) && !empty($originalText)) {
            $originalText = preg_replace('/[^\P{C}]+/u', '', $originalText);
            $counts[$originalText] = 1;
        }
            
        return $counts;
    }

    /**
     * @param resource $resource
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    public static function countFromResource($resource, array $counts = [], ?string $mimeType = null, ?string $extension = null): array
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('The provided argument is not a valid resource.');
        }
        foreach (self::FILE_STRATEGIES as $fileStrategyClass) {
            if ($fileStrategyClass::isSupported($extension, $mimeType)) {
                return $fileStrategyClass::countFromResource($resource, $counts);
            }
        }
        if ($mimeType !== null || $extension !== null) {
            return $counts;
        }
        $buffer = '';
        $chunkSize = 4096 * 1024; // Read 4MB at a time
        rewind($resource);
        while (!feof($resource)) {
            $buffer .= fread($resource, $chunkSize);
            
            $lastSpacePos = strrpos($buffer, ' ');
            if ($lastSpacePos !== false) {
                $chunk = substr($buffer, 0, $lastSpacePos);
                $buffer = substr($buffer, $lastSpacePos + 1);
                if (preg_match('/[^\P{C}]+/u', $chunk)) {
                    return $counts;
                }
                $counts = self::updateCountsFromChunk($chunk, $counts);
            }
        }

        // Process any remaining words in the buffer
        if (!empty($buffer)) {
            if (preg_match('/[^\P{C}]+/u', $buffer)) {
                return $counts;
            }
            $counts = self::updateCountsFromChunk($buffer, $counts);
        }

        return $counts;
    }

    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    private static function updateCountsFromChunk(string $originalText, array $counts): array
    {
        $chunk = mb_strtolower($originalText);
        $chunk = preg_replace('/[^\p{L}\p{N}\s.]/u', '', $chunk);
        $wordsAndNumbers = preg_split('/[\s]+/', $chunk);

        foreach ($wordsAndNumbers as $item) {
            $item = rtrim($item, '.');
            if (empty($item)) {
                continue;
            }

            if (array_key_exists($item, $counts)) {
                $counts[$item]++;
            } else {
                $counts[$item] = 1;
            }
        }

        if (empty($counts) && !empty($originalText)) {
            $originalText = preg_replace('/[^\P{C}]+/u', '', $originalText);
            $counts[$originalText] = 1;
        }

        return $counts;
    }
}
