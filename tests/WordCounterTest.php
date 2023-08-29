<?php
namespace Apie\Tests\CountWords;

use Apie\CountWords\WordCounter;
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
    
    public function textDataProvider()
    {
        return [
            [
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
            [
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
            [
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
