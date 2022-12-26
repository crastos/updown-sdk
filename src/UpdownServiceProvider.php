<?php

namespace Crastos\Updown;

use Crastos\Updown\Commands\UpdownCommand;
use Crastos\Updown\Http\Client;
use Illuminate\Support\ServiceProvider;

class UpdownServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Client::class, fn () => new Client($this->app->config->get('services.updown.secret')));
        $this->app->singleton(Updown::class, fn () => new Updown($this->app->make(Client::class)));
    }

    public function boot()
    {
        $this->commands(UpdownCommand::class);
        $this->app->alias(Updown::class, 'updown');
    }
}
