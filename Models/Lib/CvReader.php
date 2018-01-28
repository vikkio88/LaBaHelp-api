<?php


namespace App\Models\Lib;


class CvReader
{
    private $rawText;
    private $filters;
    private $exception;

    public function __construct(string $rawText, array $filters = [], array $exceptions = [])
    {
        $this->rawText = $rawText;
        $this->filters = $filters;
        $this->exception = $exceptions;
    }

    public function tokenizeFilter(string $rawText, array $filters = [], array $exceptions = []): array
    {
        $tokens = [];
        $rawText = str_replace($exceptions['punctuation'], ' ', strtolower($rawText));
        $words = array_filter(explode(' ', $rawText));
        foreach ($words as $word) {
            if (in_array($word, $exceptions['words'])) {
                continue;
            }

            $total = $tokens[$word] ?? 0;
            $total += 1;
            $tokens[$word] = $total;
        }
        arsort($tokens);
        return $tokens;
    }

    public function map(array $tokens): array
    {
        $map = [
            'role' => [],
            'language' => [],
            'framework' => []
        ];
        foreach ($tokens as $token => $count) {
            foreach ($this->filters as $category => $subcategories) {
                foreach ($subcategories as $label => $stack) {
                    echo PHP_EOL . $label . " " . $token . PHP_EOL;
                    if (in_array($token, $stack)) {
                        $total = $map[$category][$label] ?? 0;
                        $total += $count;
                        $map[$category][$label] = $total;
                    }
                }
            }
        }
        return $map;
    }

    public function skim(): array
    {
        $tokens = $this->tokenizeFilter($this->rawText, $this->filters, $this->exception);
        return $this->map($tokens);
    }
}