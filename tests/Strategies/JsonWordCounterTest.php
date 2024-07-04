<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\JsonWordCounter;
use PHPUnit\Framework\TestCase;

class JsonWordCounterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_count_words_in_json_files()
    {
        $jsonPath = __DIR__ . '/../../fixtures/json/example.json';
        $actual = JsonWordCounter::countFromFile($jsonPath);
        $expectedPath = __DIR__ . '/../../fixtures/expected-json-sample.json';
        // file_put_contents($expectedPath, json_encode($actual, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        $expected = json_decode(file_get_contents($expectedPath), true);
        $this->assertEquals($expected, $actual);
    }
}
