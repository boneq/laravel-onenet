<?php
/**
 * Created by PhpStorm.
 * User: bone
 * Date: 2017/10/17
 * Time: 16:40
 */
namespace Boneq\OneNet\Facades;
use Illuminate\Support\Facades\Facade;
class OneNet extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'onenet';
    }
}