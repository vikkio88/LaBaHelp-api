<?php
require 'vendor/autoload.php';

use App\Lib\Helpers\Config;
use App\Models\Lib\CvReader;

class CVReaderTest extends PHPUnit_Framework_TestCase
{
    public function testItTokenizeAndMapTechnologies()
    {
        $text = file_get_contents('tests/sample/cv1.txt');
        $cvR = new CvReader(
            $text,
            Config::get('map.config'),
            [
                'words' => Config::get('exceptions.words'),
                'punctuation' => Config::get('exceptions.punctuation')
            ]
        );
        var_dump($cvR->skim());
    }
}