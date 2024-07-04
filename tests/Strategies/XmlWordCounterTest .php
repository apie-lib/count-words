<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\XmlWordCounter;
use PHPUnit\Framework\TestCase;

class XmlWordCounterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_count_words_in_xml_files()
    {
        $xmlPath = __DIR__ . '/../../fixtures/xml/test.xml';
        $actual = XmlWordCounter::countFromFile($xmlPath);
        $expectedPath = __DIR__ . '/../../fixtures/expected-xml-sample.json';
        file_put_contents($expectedPath, json_encode($actual, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        $expected = json_decode(file_get_contents($expectedPath), true);
        $this->assertEquals($expected, $actual);
    }
}
