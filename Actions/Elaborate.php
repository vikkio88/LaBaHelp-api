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
            return !in_array(strtolower($word), $exceptions);
        });
        $result = [];
        foreach ($words as $word) {
            if (!isset($result[$word])) {
                $result[$word] = 0;
            }
            $result[$word] += substr_count($text, $word);
        }

        arsort($result);
        $this->payload = $result;
    }
}