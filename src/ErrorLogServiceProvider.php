<?php

namespace Shahriar\ErrorLoger;

use Illuminate\Support\ServiceProvider;

class ErrorLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes/web.php';

        //register service to non lumen app only
        if ($this->app->runningInConsole() && !$this->app->environment('lumen')) {
            $this->app->register(ErrorLogServiceProvider::class);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'errorlog');
        $this->publishes([
            __DIR__ . '/resources/views' => base_path('resources/views/acolyte/errorlog'),
            __DIR__ . '/database/migrations' => base_path('database/migrations'),
            __DIR__ . '/config' => base_path('config'),
        ]);
    }
}
