<?php

namespace Crastos\Updown\Tests;

use Crastos\Updown\UpdownServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $loadEnvironmentVariables = true;

    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            UpdownServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);

        $app->config->set('services.updown.secret', env('UPDOWN_SERVER_SECRET'));
        $app->config->set('services.updown.browser_key', env('UPDOWN_BROWSER_KEY'));
    }
}
