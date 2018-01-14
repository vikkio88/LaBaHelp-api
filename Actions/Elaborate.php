<?php

namespace App\Actions;

use App\Lib\Helpers\Config;
use App\Lib\Slime\RestAction\ApiAction;

class Elaborate extends ApiAction
{
    protected function performAction()
    {
        $text = $this->getJsonRequestBody()['text'];
        $words = str_word_count($this->getJsonRequestBody()['text'], 1);
        $exceptions = Config::get('exceptions.list');
        $words = array_filter($words, function ($word) use ($exceptions) {
            return strlen($word) > 1 && !in_array(strtolower($word), $exceptions);
        });
        $counting = [];
        foreach ($words as $word) {
            if (isset($counting[$word])) {
                continue;
            }
            $counting[$word] = substr_count($text, $word);
        }
        arsort($counting);
        $result = [];
        foreach ($counting as $value => $count) {
            $result[] = [
                'value' => $value,
                'count' => $count
            ];
        }
        $this->payload = $result;
    }
}