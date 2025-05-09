<?php
namespace Apie\CountWords\Strategies;

use Apie\CountWords\Strategies\Concerns\UseStringForResource;
use Apie\CountWords\Strategies\Concerns\UseTempFileForString;
use Apie\CountWords\WordCounter;
use ZipArchive;

final class ZipArchiveWordCounter implements WordCounterInterface
{
    use UseStringForResource;
    use UseTempFileForString;

    private static int $counter = 0;

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function isSupported(?string $fileExtension, ?string $mimeType): bool
    {
        if (!class_exists(ZipArchive::class) || self::$counter > 6) {
            return false;
        }
        return in_array(strtolower($fileExtension ?? ''), ['zip'])
            || in_array(strtolower($mimeType ?? ''), [
                'application/zip',
                'application/x-zip-compressed',
            ]);
    }

    public static function countFromFile(string $filePath, array $counts = []): array
    {
        self::$counter++;
        try {
            $zip = new ZipArchive();
            if (!$zip->open($filePath)) {
                throw new \RuntimeException('Could not open ' . $filePath);
            }
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
            
                // Open a stream to read the file content
                $stream = $zip->getStream($filename);
            
                if (!$stream) {
                    throw new \LogicException("Failed to open stream for file: $filename\n");
                }
                try {
                    $counts = WordCounter::countFromResource($stream, $counts, null, pathinfo($filename, PATHINFO_EXTENSION));
                } finally {
                    fclose($stream);
                }
            }
            return $counts;
        } finally {
            self::$counter--;
        }
    }
}
