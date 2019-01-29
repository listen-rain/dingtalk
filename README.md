# dingtalk 是基于 laravel5.5 开发的钉钉机器人扩展包

> 仅支持 Laravel 5 框架

```
当前自定义机器人支持
文本（text）、链接（link）、markdown（markdown）三种消息类型
大家可以根据自己的使用场景选择合适的消息类型，达到最好的展示样式
```

# 安装方法

### 1、安装

```
composer require listen/dingtalk
```
 
###  2、配置app.php

在config/app.php 'providers' 中添加 
```
\Listen\DingTalk\DingTalkServiceProvider::class,
```

在config/app.php 'aliases' 中添加
```
'DingTalk' => Listen\DingTalk\Facades\DingTalk::class,
```

###  3、生成配置文件 config/dingtalk.php

```
php artisan vendor:publish 
```

# 使用

### 实例化
```
$message = app('message')
或
$message = app(Listen\DingTalk\Message::class)

$dingtalk = app('dingtalk')
或
$dingtalk = app(Listen\DingTalk\DingTalk::class)
```

### 实现Text发送

```
$message = app('message')->text("laravel error");
app('dingtalk')->send($message);
```

### 实现Link发送
```
$DingTalk = new DingTalk();
$message = new Message();
$title = '测试link类型title';
$text = '测试link类型text';
$messageUrl = 'https://www.baidu.com/';
$picUrl = '';
$data = $message->link($title, $text, $messageUrl, $picUrl);
$DingTalk->send($data);
```

### 实现Markdown发送
```
$message = app('message')->markdown('test', "laravel error");
app('dingtalk')->send($message);
```

### 使用帮助函数
```
sendByDingtalk('test message', 'test');
```

### 联系我

邮箱：zhufengwei@aliyun.com

微信：w15275049388
