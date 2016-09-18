<?php

namespace LaravelPropertyBag;

use Auth;
use LaravelPropertyBag\Helpers\NameResolver;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $namespace = NameResolver::getUserSettingsNamespace();

        $this->app->singleton($namespace, function () {
            return Auth::user()->settings();
        });

        $this->registerCommands();
    }

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('laravel-property-bag.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/Migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register Artisan commands.
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.pbag.make', function ($app) {
            return $app['LaravelPropertyBag\Commands\PublishSettingsConfig'];
        });

        $this->commands('command.pbag.make');
    }
}
