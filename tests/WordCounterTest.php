<?php
namespace Apie\Tests\CountWords;

use Apie\CountWords\WordCounter;
use Generator;
use PHPUnit\Framework\TestCase;

class WordCounterTest extends TestCase
{
    /**
     * @dataProvider textDataProvider
     */
    public function testCountFromString(string $text, array $expectedCounts)
    {
        $counts = WordCounter::countFromString($text);
        
        $this->assertEquals($expectedCounts, $counts);
    }

    /**
     * @dataProvider textDataProvider
     */
    public function testCountFromResource(string $text, array $expectedCounts)
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $text);
        rewind($resource);
        $counts = WordCounter::countFromResource($resource);
        
        $this->assertEquals($expectedCounts, $counts);
    }
    
    public function textDataProvider()
    {
        return [
            'simple test 1' => [
                "This is a sample text. 123 3.14 456 This text contains sample words.",
                [
                    "this" => 2,
                    "is" => 1,
                    "a" => 1,
                    "sample" => 2,
                    "text" => 2,
                    "123" => 1,
                    "3.14" => 1,
                    "456" => 1,
                    "contains" => 1,
                    "words" => 1
                ]
            ],
            'simple test 2' => [
                "Another test with some more words. 123 123 456",
                [
                    "another" => 1,
                    "test" => 1,
                    "with" => 1,
                    "some" => 1,
                    "more" => 1,
                    "words" => 1,
                    "123" => 2,
                    "456" => 1
                ]
            ],
            'single non-alphanumeric character' => [
                '%',
                [
                    '%' => 1,
                ]
            ],
            'contains foreign characters' => [
                "This is a 你好 sample text. 123 3.14 你好 456 This text contains 你好 sample words.",
                [
                    "this" => 2,
                    "is" => 1,
                    "a" => 1,
                    "你好" => 3,
                    "sample" => 2,
                    "text" => 2,
                    "123" => 1,
                    "3.14" => 1,
                    "456" => 1,
                    "contains" => 1,
                    "words" => 1
                ]
            ],
        ];
    }

    /**
     * @dataProvider officeDataProvider
     * @requires extension zip
     */
    public function testCountFromOfficeDocument(string $path, array $expectedCounts)
    {
        $actual = WordCounter::countFromOfficeXMLDocument($path);
        $this->assertEquals(
            $expectedCounts,
            $actual
        );
    }

    public function officeDataProvider(): Generator
    {
        $fixturesPath = __DIR__ . '/../fixtures/office/';
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
            json_decode(file_get_contents($fixturesPath . '/../expected-document-with-image.json'), true)
        ];
    }
}
