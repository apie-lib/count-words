<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\PlaintextWordCounter;
use PHPUnit\Framework\TestCase;

class PlaintextWordCounterTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_count_words_in_text_files()
    {
        $txtPath = __DIR__ . '/../../fixtures/txt/example.txt';
        $actual = PlaintextWordCounter::countFromFile($txtPath);
        $expected = [
            'this' => 1,
            'is' => 1,
            'an' => 1,
            'example' => 1,
            'of' => 1,
            'a' => 1,
            'plaintext' => 1,
            'file' => 1,
        ];
        $this->assertEquals($expected, $actual);
    }
}
