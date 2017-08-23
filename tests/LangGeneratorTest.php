<?php

namespace Novius\CaouecsLangExtended\Tests;

use Illuminate\Support\Facades\Artisan;
use Mockery as m;
use Novius\Caouecs\Lang\LangGeneratorServiceProvider;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;

class LangGeneratorTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LangGeneratorServiceProvider::class,
        ];
    }

    /**
     * Does the new command lang:install exists? This doesn't check if this command actually work.
     */
    public function testLangInstallCommandExists()
    {
        $commands = Artisan::all();
        $this->assertArrayHasKey('lang:install', $commands);
    }

    /**
     * Does lang:install command is executable? This doesn't check if this command actually work.
     */
    public function testLaunchCommandWithoutException()
    {
        $this->artisan('lang:install', ['local' => 'fr', '--force' => true]);
    }

    public function testLaunchCommandWithoutArgumentFails()
    {
        $this->expectException(RuntimeException::class);
        $this->artisan('lang:install');
    }

    public function testLaunchCommandWithUnavailableLocalFails()
    {
        $command = m::mock('\Novius\Caouecs\Lang\Console\LanguageGeneratorCommand[error]', [[]]);
        $command->shouldReceive('error')->once()->with('The language wanted doesn\'t exists.');
        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);
        $this->artisan('lang:install', ['local' => 'xyz', '--force' => false]);
    }
}
