<?php


use App\Lib\Slime\Interfaces\DatabaseHelpers\DbHelperInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;

class M1515926975Log implements DbHelperInterface
{

    public function run()
    {
        $tableName = 'log';
        Capsule::schema()->dropIfExists($tableName);
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('ua');
            $table->string('ip');
            $table->text('text');
            $table->text('result');
            $table->timestamps();
        });
    }

}