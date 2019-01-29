<?php

namespace Listen\DingTalk;

use Listen\DingTalk\Exceptions\DingTalkException;

class Message
{
    /**
     * @var string
     */
    protected $msgType;

    /**
     * @var array
     * @desc 被@人的手机号(在text内容里要有@手机号)
     */
    protected $atMobiles = [];

    /**
     * @var bool
     * @desc @所有人时:true,否则为:false
     */
    protected $isAtAll = 'false';

    /**
     * @var array
     */
    public $content = [];

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     * @单条信息后面图片的URL
     */
    protected $picUrl;

    /**
     * @var string
     * @点击单条信息到跳转链接
     */
    protected $messageUrl;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->atMobiles = config('dingtalk.atMobiles', []);
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param string $content
     *
     * @return $this
     */
    public function text(string $content)
    {
        $this->msgType            = 'text';
        $this->content['content'] = $content;

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param string $title
     * @param string $text
     * @param string $messageUrl
     * @param string $picUrl
     *
     * @return $this
     */
    public function link(string $title, string $text, string $messageUrl, string $picUrl = '')
    {
        $this->msgType      = 'link';
        $data               = [];
        $data['title']      = $title;
        $data['text']       = $text;
        $data['picUrl']     = $picUrl;
        $data['messageUrl'] = $messageUrl;
        $this->content      = $data;

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param string $title
     * @param string $text
     * @param bool   $format
     *
     * @return $this
     */
    public function markdown(string $title, string $text, $format = true)
    {
        $this->msgType = 'markdown';
        $this->content = [
            'title' => $title,
            'text'  => $format ? "## {$title}\n---\n{$text}" : $text
        ];

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param string $title
     * @param string $text
     * @param string $singleTitle
     * @param string $singleURL
     * @param string $btnOrientation
     * @param string $hideAvatar
     *
     * @return $this
     */
    public function actionCard(string $title, string $text, string $singleTitle, string $singleURL, string $btnOrientation = '0', string $hideAvatar = '0')
    {
        $this->msgType = 'actionCard';
        $data          = [];
        $data['title'] = $title;
        $data['text']  = $text;
        // 单个按钮的方案。(设置此项和singleURL后btns无效。)
        $data['singleTitle'] = $singleTitle;
        // 点击singleTitle按钮触发的URL
        $data['singleURL'] = $singleURL;
        // 	0-按钮竖直排列，1-按钮横向排列
        $data['btnOrientation'] = $btnOrientation;
        // 0-正常发消息者头像,1-隐藏发消息者头像
        $data['hideAvatar'] = $hideAvatar;
        $this->content      = $data;
        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param array $cards
     *
     * @return $this
     */
    public function feedCard(array $cards)
    {
        $this->msgType = 'feedCard';
        $data['links'] = $cards;
        $this->content = $data;

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @return $this
     */
    public function atAll()
    {
        $this->isAtAll = 'true';

        if (isset($this->content['text'])) {
            $this->content['text'] .= "\n\n@所有人";
        }

        if (isset($this->content['content'])) {
            $this->content['content'] .= "\n\n@所有人";
        }

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param array $mobiles
     *
     * @return $this
     */
    public function atMobiles(array $mobiles)
    {
        $this->atMobiles = $mobiles;

        return $this;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @return string
     */
    public function getMsgType()
    {
        return $this->msgType;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @return array|mixed
     */
    public function getAtMobiles()
    {
        return $this->atMobiles;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @return bool
     */
    public function getIsAtAll()
    {
        return $this->isAtAll;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     * @return array
     */
    public function at(): array
    {
        return [
            'atMobiles' => $this->atMobiles,
            'isAtAll'   => strval($this->isAtAll)
        ];
    }

    public function atAble()
    {
        $atable = false;

        switch ($this->msgType) {
            case 'text':
                $atable = isset($this->content['content']);
                break;
            case 'markdown':
                $atable = isset($this->content['text']);
                break;
            case 'link':
                $atable = isset($this->content['text']);
                break;
            case 'actionCard':
                $atable = isset($this->content['text']);
                break;
            case 'feedCard':
                break;
            default:
                break;
        }

        if (!empty($this->atMobiles)) {
            return $atable;
        }

        return false;
    }

    /**
     * @date   2019/1/29
     * @author <zhufengwei@aliyun.com>
     *
     * @param \Listen\DingTalk\Message $message
     *
     * @return string
     */
    public function buildRequestPayload()
    {
        if (!$this->msgType) {
            throw new DingTalkException('Message Type Con\'t Be Null !');
        }

        if ($this->atAble()) {
            foreach ($this->atMobiles as $mobile) {
                if ($this->msgType === 'text') {
                    $this->content['content'] .= "\n\n@{$mobile}";
                } else {
                    $this->content['text'] .= "\n\n@{$mobile}";
                }
            }
        }

        $data['at']           = $this->at();
        $data['msgtype']      = $this->msgType;
        $data[$this->msgType] = $this->content;

        return json_encode($data);
    }
}
