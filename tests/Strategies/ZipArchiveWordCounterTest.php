<?php
namespace Apie\Tests\CountWords\Strategies;

use Apie\CountWords\Strategies\ZipArchiveWordCounter;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

class ZipArchiveWordCounterTest extends TestCase
{
    #[RequiresPhpExtension('zip')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_count_words_in_zip_files()
    {
        $zipPath = __DIR__ . '/../../fixtures/zip/fixtures.zip';
        $actual = ZipArchiveWordCounter::countFromFile($zipPath);
        $expectedPath = __DIR__ . '/../../fixtures/expected-zip-sample.json';
        // file_put_contents($expectedPath, json_encode($actual, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        $expected = json_decode(file_get_contents($expectedPath), true);
        $this->assertEquals($expected, $actual);
    }

    #[RequiresPhpExtension('zip')]
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_reads_zips_inside_zips_with_limited_recursion()
    {
        // can not provide the file on the repo, considering it is not my zip file.
        $zipPath = __DIR__ . '/../../fixtures/zip/droste.zip';
        if (!file_exists($zipPath)) {
            $fileContents = @file_get_contents('https://alf.nu/s/droste.zip');
            if ($fileContents === false) {
                $this->markTestSkipped('Could not run test for missing download');
            }
            file_put_contents($zipPath, $fileContents);
        }
        $actual = ZipArchiveWordCounter::countFromFile($zipPath);
        $this->assertEquals([], $actual);
    }
}
