<?php
/**
 * Created by PhpStorm.
 * User: <zhufengwei@aliyun.com>
 * Date: 2019/1/29
 * Time: 17:20
 */

if (!function_exists('sendByDingtalk')) {
    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @param string $message
     * @param string $title
     */
    function sendByDingtalk(string $message = '', string $title = '钉钉报警')
    {
        app(\Listen\DingTalk\DingTalk::class)->send(
            app(\Listen\DingTalk\Message::class)->markdown($title, $message)
        );
    }
}
