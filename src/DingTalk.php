<?php

namespace Listen\DingTalk;

use Listen\DingTalk\Exceptions\DingTalkException;

class DingTalk
{
    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var array
     */
    private $headers = [];

    /**
     * DingTalk constructor.
     */
    public function __construct()
    {
        $this->domain  = config('dingtalk.domain') ?: 'https://oapi.dingtalk.com/robot/send?access_token=' . config('dingtalk.token');
        $this->headers = ['Content-Type: application/json;charset=utf-8'];
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @param \Listen\DingTalk\Message $message
     *
     * @return bool
     * @throws \Listen\DingTalk\Exceptions\DingTalkException
     */
    public function send(Message $message)
    {
        if (!$this->domain) {
            throw new DingTalkException('Token\'s Empty!', 901001);
        }

        $body = $message->buildRequestPayload();

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->domain);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $resp = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($resp);
            if ($data->errcode === 0) {
                return true;
            }

            throw new DingTalkException($data->errmsg, $data->errcode);

        } catch (DingTalkException $e) {
            throw $e;
        }
    }
}
