<?php

namespace Listen\DingTalk\Providers;

use Illuminate\Support\ServiceProvider;
use Listen\DingTalk\DingTalk;
use Listen\DingTalk\Message;

class DingTalkServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../../config/dingtalk.php' => config_path('dingtalk.php'),
            ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/dingtalk.php', 'dingtalk'
        );

        $this->app->singleton('dingtalk', function ($app) {
            return new DingTalk();
        });

        $this->app->bind('message', function ($app) {
            return new Message();
        });
    }
}
