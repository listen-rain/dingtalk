<?php
/**
 * Created by PhpStorm.
 * User: <zhufengwei@aliyun.com>
 * Date: 2019/1/29
 * Time: 10:21
 */

namespace Listen\DingTalk\Facades;

use Illuminate\Support\Facades\Facade;

class DingTalk extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'dingtalk';
    }
}
