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
            
        return $counts;
    }
}
