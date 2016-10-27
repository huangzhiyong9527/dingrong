<?php

namespace Nhtm\Dingrong;

use Illuminate\Support\ServiceProvider;

class DingrongServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/dingrong.php' => config_path('dingrong.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContact();
        config([
            'config/dingrong.php',
        ]);
    }

    private function registerContact()
    {
        $this->app->bind('dingrong', function ($app) {
            return new dingrong($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['dingrong'];
    }
}
