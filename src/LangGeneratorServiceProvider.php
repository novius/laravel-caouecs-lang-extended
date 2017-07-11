<?php

namespace Novius\Caouecs\Lang;

use Illuminate\Support\ServiceProvider;
use Novius\Caouecs\Lang\Console\LanguageGeneratorCommand;

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

        if ($this->app->runningInConsole()) {
            $this->commands([
                LanguageGeneratorCommand::class,
            ]);
        }
    }
}
