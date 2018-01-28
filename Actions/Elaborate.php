<?php

namespace App\Actions;

use App\Lib\Helpers\Config;
use App\Lib\Slime\RestAction\ApiAction;
use App\Models\Lib\CvReader;
use App\Models\Log;

class Elaborate extends ApiAction
{
    protected function performAction()
    {
        $body = $this->getJsonRequestBody();
        $text = $body['text'];
        $cvReader = new CvReader(
            $text,
            Config::get('map.config'),
            [
                'words' => Config::get('exceptions.words'),
                'punctuation' => Config::get('exceptions.punctuation')
            ]
        );
        $result = $cvReader->skim();

        try {
            Log::create(array_merge(
                $body,
                [
                    'ip' => $this->request->getAttribute('ip_address'),
                    'result' => json_encode($result)
                ]
            ));
        } catch (\Exception $e) {
            //yummy yummy
        }

        $normalizedResult = [];
        foreach ($result as $category => $subresult) {
            $temp = [
                'name' => $category,
                'results' => []
            ];
            foreach ($subresult as $value => $count) {
                $temp['results'][] = [
                    'value' => $value,
                    'count' => $count,
                ];
            }
            $normalizedResult[] = $temp;
        }

        $this->payload = $normalizedResult;
    }
}