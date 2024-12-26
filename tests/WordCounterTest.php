<?php
namespace Apie\Tests\CountWords;

use Apie\CountWords\WordCounter;
use PHPUnit\Framework\TestCase;

class WordCounterTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('fileDataProvider')]
    public function testCountFromFile(string $file, array $expectedCounts)
    {
        $counts = WordCounter::countFromFile($file);
        
        $this->assertEquals($expectedCounts, $counts);
    }

    public static function fileDataProvider()
    {
        $path = __DIR__ . '/../fixtures/';
        yield 'html file' => [$path . '/html/test.html', json_decode(file_get_contents($path . 'expected-html-sample.json'), true)];
        yield 'text file' => [
            $path . '/txt/example.txt',
            [
                'this' => 1,
                'is' => 1,
                'an' => 1,
                'example' => 1,
                'of' => 1,
                'a' => 1,
                'plaintext' => 1,
                'file' => 1,
            ]
        ];
        yield 'unknown binary format' => [$path . 'unknown-binary-format.bin', []];
    }
    
    #[\PHPUnit\Framework\Attributes\DataProvider('textDataProvider')]
    public function testCountFromString(string $text, array $expectedCounts)
    {
        $counts = WordCounter::countFromString($text);
        
        $this->assertEquals($expectedCounts, $counts);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('textDataProvider')]
    public function testCountFromResource(string $text, array $expectedCounts)
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $text);
        rewind($resource);
        $counts = WordCounter::countFromResource($resource);
        
        $this->assertEquals($expectedCounts, $counts);
    }
    
    public static function textDataProvider()
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
}
