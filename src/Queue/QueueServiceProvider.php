<?php

namespace Khronos\MongoDB\Queue;

use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend('queue', function ($manager) {
            $manager->extend('mongodb', function () {
                return new Connector($this->app['mongodb']);
            });

            return $manager;
        });
    }
}
