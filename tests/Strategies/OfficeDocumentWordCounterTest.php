<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\OfficeDocumentWordCounter;
use Generator;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

class OfficeDocumentWordCounterTest extends TestCase
{
    #[RequiresPhpExtension('zip')]
    #[\PHPUnit\Framework\Attributes\DataProvider('officeDataProvider')]
    public function testCountFromOfficeDocument(string $path, array $expectedCounts)
    {
        $actual = OfficeDocumentWordCounter::countFromFile($path);
        $this->assertEquals(
            $expectedCounts,
            $actual
        );
    }

    public static function officeDataProvider(): Generator
    {
        $fixturesPath = __DIR__ . '/../../fixtures/office/';
        yield 'word document' => [
            $fixturesPath . 'document.docx',
            ['lorum' => 1, 'ipsum' => 1]
        ];
        yield 'excel file' => [
            $fixturesPath . 'spreadsheet.xlsx',
            [
                1 => 1,
                2 => 1,
                'this' => 1,
                'is' => 1,
                'a' => 1,
                'test' => 1,
                'another' => 1,
                'field' => 1,
                'hello' => 1,
                // TODO: sheets
                // TODO: why 1 and 2?
            ]
        ];
        yield 'complex word document with images' => [
            $fixturesPath . 'document-with-image.docx',
            json_decode(file_get_contents($fixturesPath . '../expected-document-with-image.json'), true)
        ];
    }
}
