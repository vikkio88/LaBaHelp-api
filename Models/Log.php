<?php

namespace App\Models;

use App\Lib\Slime\Models\SlimeModel;

class Log extends SlimeModel
{
    protected $table = 'log';
    protected $fillable = [
        'ua',
        'ip',
        'text',
        'result'
    ];
}