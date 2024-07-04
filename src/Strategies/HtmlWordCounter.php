<?php
namespace Apie\CountWords\Strategies;

use Apie\CountWords\Strategies\Concerns\UseResourceForFile;
use Apie\CountWords\Strategies\Concerns\UseStringForResource;
use Apie\CountWords\WordCounter;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;

final class HtmlWordCounter implements WordCounterInterface
{
    use UseStringForResource;
    use UseResourceForFile;

    private const INLINE_ELEMENTS = [
        'a',
        'abbr',
        'acronym',
        'b',
        'bdo',
        'big',
        'button',
        'cite',
        'code',
        'dfn',
        'em',
        'i',
        'img',
        'input',
        'kbd',
        'label',
        'map',
        'object',
        'output',
        'q',
        'samp',
        'script',
        'select',
        'small',
        'span',
        'strong',
        'sub',
        'sup',
        'textarea',
        'time',
        'tt',
        'var',
    ];

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function isSupported(?string $fileExtension, ?string $mimeType): bool
    {
        return in_array($fileExtension, ['html', 'xhtml', 'htm']) || in_array($mimeType, ['text/html', 'application/xhtml+xml']);
    }

    /**
     * @param DOMNodeList<DOMElement> $nodes
     * @return array<int, string>
     */
    private static function extractAttributeValues(DOMNodeList|false $nodes, string $attribute): array
    {
        if (!$nodes) {
            return [];
        }
        $values = [];
        foreach ($nodes as $node) {
            $values[] = (string) $node->getAttribute($attribute);
        }
        return $values;
    }

    public static function countFromString(string $text, array $counts = []): array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($text);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        $altNodes = $xpath->query("//*[@alt]");
        $titleNodes = $xpath->query("//*[@title]");
        $labelNodes = $xpath->query("//*[@label]");

        $altTexts = self::extractAttributeValues($altNodes, 'alt');
        $titleTexts = self::extractAttributeValues($titleNodes, 'title');
        $labelTexts = self::extractAttributeValues($labelNodes, 'label');

        $allTexts = array_merge($altTexts, $titleTexts, $labelTexts);

        foreach ($allTexts as $attributeText) {
            $counts = WordCounter::countFromString($attributeText, $counts);
        }

        $text = strip_tags(str_replace(['<', '>'], [' <', '> '], $text), self::INLINE_ELEMENTS);
        $text = html_entity_decode(strip_tags(str_replace([' <', '> '], ['<', '>'], $text)));

        return WordCounter::countFromString($text, $counts);
    }
}
