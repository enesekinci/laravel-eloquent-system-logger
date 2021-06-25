<?php

namespace EnesEkinci\EloquentSystemLogger;

use Illuminate\Support\ServiceProvider;

class EloquentSystemLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function register()
    {
    }
}
