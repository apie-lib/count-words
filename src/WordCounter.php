<?php
namespace Apie\CountWords;

class WordCounter
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
    
    /**
     * @return array<string, int>
     */
    public static function countFromString(string $text): array
    {
        $originalText = mb_strtolower(trim($text));
        // Convert the text to lowercase to make counting case-insensitive
        $text = mb_strtolower($text);
            
        // Remove punctuation marks and symbols
        $text = preg_replace('/[^\p{L}\p{N}\s.]/u', '', $text);
        
        // Split the text into an array of words and numbers
        $wordsAndNumbers = preg_split('/\s+/', $text);
            
        // Initialize an empty array to store word and number frequencies
        $counts = [];
            
        // Iterate through each word/number and count its frequency
        foreach ($wordsAndNumbers as $item) {
            // we could not remove '.' beforehand all the time as a floating point would lose the '.' as well.
            $item = rtrim($item, '.');
            if (empty($item)) {
                continue; // Skip empty strings
            }
                
            if (array_key_exists($item, $counts)) {
                $counts[$item]++;
            } else {
                $counts[$item] = 1;
            }
        }

        if (empty($counts) && !empty($originalText)) {
            $counts[$originalText] = 1;
        }
            
        return $counts;
    }

    /**
     * @param resource $resource
     * @return array<string, int>
     */
    public static function countFromResource($resource): array
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('The provided argument is not a valid resource.');
        }

        /** @var array<string, int> $counts */
        $counts = [];
        $buffer = '';
        $chunkSize = 4096; // Read 4KB at a time

        while (!feof($resource)) {
            $buffer .= fread($resource, $chunkSize);
            
            // Process whole words from the buffer
            $lastSpacePos = strrpos($buffer, ' ');
            if ($lastSpacePos !== false) {
                $chunk = substr($buffer, 0, $lastSpacePos);
                $buffer = substr($buffer, $lastSpacePos + 1);

                // Update word counts
                $counts = self::updateCountsFromChunk($chunk, $counts);
            }
        }

        // Process any remaining words in the buffer
        if (!empty($buffer)) {
            $counts = self::updateCountsFromChunk($buffer, $counts);
        }

        return $counts;
    }

    /**
     * @param array<string, int> $counts
     * @return array<string, int>
     */
    private static function updateCountsFromChunk(string $originalText, array $counts): array
    {
        $chunk = mb_strtolower($originalText);
        $chunk = preg_replace('/[^\p{L}\p{N}\s.]/u', '', $chunk);
        $wordsAndNumbers = preg_split('/\s+/', $chunk);

        foreach ($wordsAndNumbers as $item) {
            $item = rtrim($item, '.');
            if (empty($item)) {
                continue;
            }

            if (array_key_exists($item, $counts)) {
                $counts[$item]++;
            } else {
                $counts[$item] = 1;
            }
        }

        if (empty($counts) && !empty($originalText)) {
            $counts[$originalText] = 1;
        }

        return $counts;
    }
}
