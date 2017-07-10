<?php

namespace Novius\CaouecsLangExtended\Tests;

use Mockery as m;
use Novius\Caouecs\Lang\LangGeneratorServiceProvider;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;

class LangGeneratorTest extends TestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        $app->register(LangGeneratorServiceProvider::class);

        return $app;
    }

    public function testCallWithoutArgument()
    {
        try {
            $this->artisan('lang:install');
        } catch (RuntimeException $e) {
            $this->assertTrue(true);

            return;
        }
        $this->assertTrue(false);
    }

    public function testCallWithUnavailableLocal()
    {
        $command = m::mock('\Novius\Caouecs\Lang\Console\LanguageGeneratorCommand[error]', [[]]);
        $command->shouldReceive('error')->once()->with('The language wanted doesn\'t exists.');
        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);
        $this->artisan('lang:install', ['local' => 'xyz', '--force' => false]);
    }
}
