<?php
namespace Apie\CountWords\Strategies;

use Apie\CountWords\Strategies\Concerns\UseStringForResource;
use Apie\CountWords\Strategies\Concerns\UseTempFileForString;
use Apie\CountWords\WordCounter;
use Smalot\PdfParser\Parser;

final class PdfFileWordCounter implements WordCounterInterface
{
    use UseStringForResource;
    use UseTempFileForString;
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function isSupported(?string $fileExtension, ?string $mimeType): bool
    {
        if (!class_exists(Parser::class)) {
            return false;
        }
        return $fileExtension === 'pdf' || $mimeType === 'application/pdf';
    }

    public static function countFromFile(string $path, array $counts = []): array
    {
        $parser = new Parser();
        try {
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
        } catch (\Exception) {
            return $counts;
        }
        $result = WordCounter::countFromString($text, $counts);
        try {
            $details = $pdf->getDetails();
            unset($details['CreationDate']);
            unset($details['ModDate']);
            unset($details['Pages']);
            unset($details['Producer']);
            foreach ($details as $detail) {
                $result = WordCounter::countFromString($detail, $result);
            }
        } catch (\Exception) {
        }
        return $result;
    }
}
