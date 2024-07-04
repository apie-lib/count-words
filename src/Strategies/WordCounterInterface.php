<?php
namespace Apie\CountWords\Strategies;

interface WordCounterInterface
{
    public static function isSupported(?string $fileExtension, ?string $mimeType): bool;
    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    public static function countFromFile(string $path, array $counts = []): array;
    /**
     * @param resource $resource
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    public static function countFromResource(mixed $resource, array $counts = []): array;

    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    public static function countFromString(string $text, array $counts = []): array;
}
