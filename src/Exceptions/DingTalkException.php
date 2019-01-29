<?php
/**
 * Created by PhpStorm.
 * User: <zhufengwei@aliyun.com>
 * Date: 2019/1/29
 * Time: 09:45
 */

namespace Listen\DingTalk\Exceptions;

class DingTalkException extends \Exception
{
    /**
     * DingTalkException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
