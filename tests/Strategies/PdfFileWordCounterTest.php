<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\PdfFileWordCounter;
use PHPUnit\Framework\TestCase;

class PdfFileWordCounterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_count_words_in_pdf_files()
    {
        $pdfPath = __DIR__ . '/../../fixtures/pdf/sample.pdf';
        $actual = PdfFileWordCounter::countFromFile($pdfPath);
        $expectedPath = __DIR__ . '/../../fixtures/expected-pdf-sample.json';
        // file_put_contents($expectedPath, json_encode($actual, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        $expected = json_decode(file_get_contents($expectedPath), true);
        $this->assertEquals($expected, $actual);
    }
}
