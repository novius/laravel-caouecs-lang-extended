<?php

namespace Novius\CaouecsLangExtended\Tests;

use Mockery as m;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;

class LangGeneratorTest extends TestCase
{
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

    public function testCallWithGoodLocalAndForce()
    {
        $command = m::mock('\Novius\Caouecs\Lang\Console\LanguageGeneratorCommand[info]', [[]]);
        $command->shouldReceive('info')->once()->with(matchesPattern('/successfully installed/'));
        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);
        $this->artisan('lang:install', ['local' => 'fr', '--force' => true]);
    }
}
