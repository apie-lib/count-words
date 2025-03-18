<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\HtmlWordCounter;
use PHPUnit\Framework\TestCase;

class HtmlWordCounterTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_count_words_in_html_files()
    {
        $htmlPath = __DIR__ . '/../../fixtures/html/test.html';
        $actual = HtmlWordCounter::countFromFile($htmlPath);
        $expectedPath = __DIR__ . '/../../fixtures/expected-html-sample.json';
        // file_put_contents($expectedPath, json_encode($actual, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        $expected = json_decode(file_get_contents($expectedPath), true);
        $this->assertEquals($expected, $actual);
    }
}
