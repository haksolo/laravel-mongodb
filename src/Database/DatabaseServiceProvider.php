<?php

namespace Khronos\MongoDB\Database;

use Khronos\MongoDB\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['mongodb']);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mongodb', function ($app) {
            return new DatabaseManager($app);
        });
    }
}
