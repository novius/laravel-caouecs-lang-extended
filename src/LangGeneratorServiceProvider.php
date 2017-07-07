<?php

namespace Novius\CaouecsLangExtended;

use Illuminate\Support\ServiceProvider;
use Novius\CaouecsLangExtended\Console\Commands\LanguageGenerator;

class LangGeneratorServiceProvider extends ServiceProvider
{
    protected static $configName = 'lang-generator';
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/'.static::$configName.'.php' => config_path(static::$configName.'.php'),
        ]);
    }

    public function register()
    {
        $configPath = __DIR__.'/../config/'.static::$configName.'.php';
        $this->mergeConfigFrom($configPath, static::$configName);

        $this->registerCommand();
    }

    protected function registerCommand()
    {
        $this->app->singleton('command.cacouecs-lang-extended.install', function ($app) {
            return new LanguageGenerator(array_get($app, 'config.'.static::$configName, []));
        });
        $this->commands('command.cacouecs-lang-extended.install');
    }

    public function provides()
    {
        return ['command.cacouecs-lang-extended.install'];
    }
}
